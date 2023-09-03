<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Comments;
use App\Entity\Opinions;
use App\Entity\Tags;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\CreateArticleFormType;
use App\Repository\ArticlesRepository;
use App\Service\ArrayFlattener;
use App\Service\MentionsAndTagsReplacer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private MentionsAndTagsReplacer $mentionsAndTagsReplacer,
    ) {
    }

    #[Route('/articles', name: 'app_articles', methods: ['GET'])]
    public function articles(): Response
    {
        $articles = $this->em->getRepository(Articles::class)->findAll();

        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article', requirements: ['id' => '\d+'], defaults: ['id' => 0], methods: ['GET'])]
    public function index($id, Request $request, FormFactoryInterface $formFactory): Response
    {

        $article = $this->em->getRepository(Articles::class)->find($id);

        if (empty($article)) {
            return $this->redirectToRoute('app_home');
        }

        $article->setContent(
            $this->mentionsAndTagsReplacer->replaceMentions($article->getContent(), $article->getMentions()->toArray())
        );
        $article->setContent(
            $this->mentionsAndTagsReplacer->replaceTags($article->getContent())
        );

        $opinions = $article->getOpinions()->toArray();
        $opinionsAggregation = array_reduce($opinions, function ($opinions, $opinion) {
            if ((int) $opinion->getOpinionValue() === 1) {
                $opinions['likes'] = isset($opinions['likes']) ? $opinions['likes'] + $opinion->getOpinionValue() : $opinion->getOpinionValue();
            } else {
                //Substraction because we want the absolute value
                $opinions['dislikes'] = isset($opinions['dislikes']) ? $opinions['dislikes'] - $opinion->getOpinionValue() : $opinion->getOpinionValue();
            }
            return $opinions;
        }, ['likes' => 0, 'dislikes' => 0]);

        $comments = $this->em->getRepository(Comments::class)->findBy(['from_article' => $article, 'replies_to' => NULL]);
        foreach ($comments as $comment) {
            $comment->setContent($this->mentionsAndTagsReplacer->replaceMentions($comment->getContent(), $comment->getMentions()->toArray()));
            $comment->setContent($this->mentionsAndTagsReplacer->replaceTags($comment->getContent()));
        }
        //dd($comments);
        $numberOfRepliesPerComment = $this->em->getRepository(Comments::class)->getNumberOfReplies($article);
        //dd($numberOfRepliesPerComment);

        $is_admin = $this->isGranted('ROLE_ADMIN');
        $user_id = $this->getUser()?->getId();

        if ($this->getUser() and $user_id) {
            if ($article->getUser()->getId() === $user_id or $is_admin) {

                $articleSchema = new Articles();

                //generating the deletion form

                $articleDeletionForm = $this->createFormBuilder($articleSchema)
                    ->add('deleteButton', SubmitType::class, ['label' => 'Supprimer l\'article'])
                    ->getForm();

                //generating the edition form (same than the creation form)

            }

            $comment = new Comments();

            $commentForm = $this->createForm(
                CommentType::class,
                new Comments(),
            );

            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() and $commentForm->isValid()) {
                $comment = $commentForm->getData();
                $comment->setAuthor($this->getUser());
                $comment->setFromArticle($article);
                //dd($comment);
                $this->em->persist($comment);
                $this->em->flush();
                return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
            }

            $commentDeletionForm = $formFactory->createNamedBuilder('commentDeletion')
                ->add('deleteButton', SubmitType::class, ['label' => 'Supprimer le commentaire'])
                ->getForm()
            ;

            $opinionAdderForm = $formFactory->createNamedBuilder('opinionAdder')
                ->add('likeButton', SubmitType::class, ['label' => 'J\'aime'])
                ->add('dislikeButton', SubmitType::class, ['label' => 'Je n\'aime pas'])
                ->getForm()
            ;

            return $this->render('article/index.html.twig', [
                'article' => $article,
                'likes' => $opinionsAggregation['likes'] ?? 0,
                'dislikes' => $opinionsAggregation['dislikes'] ?? 0,
                'articleDeletionForm' => $articleDeletionForm,
                'commentForm' => $commentForm,
                'comments' => $comments ?? null,
                'numberOfRepliesPerComment' => $numberOfRepliesPerComment,
                'commentDeletionForm' => $commentDeletionForm,
                'opinionAdderForm' => $opinionAdderForm,
                //comments ids that the user can edit or delete (his own or all if he's an admin (deletion only in this case))
                'userId' => $user_id,
                'isAdmin' => $is_admin,
            ]);

        }

        /* if ($is_admin) {
            //can delete all comments but cannot edit only his own
            $commentEditionIds[] = 0;
        }

        foreach ($comments as $comment) {
            //get the comments the user can edit or delete
            if ($comment_id = $comment->getAuthor()->getId() === $user_id) {
                $commentEditionIds[] = $comment_id;
            }
        } */
        
        //dd($this->getUser());

        return $this->render('article/index.html.twig', [
            'article' => $article,
            'likes' => $opinionsAggregation['likes'] ?? 0,
            'dislikes' => $opinionsAggregation['dislikes'] ?? 0,
            'comments' => $comments ?? null,
            //comments ids that the user can edit or delete (his own or all if he's an admin (deletion only in this case))
        ]);
    }

    #[Route('/articles/create', name: 'app_article_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if (null === $this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $article = new Articles();

        $categories = $this->em->getRepository(Categories::class)->findAll();
        //$categories = $em->getRepository(Categories::class)->findAllNames();
        //$categoriesNames = $af->flattenArray($categories);
        //$categories = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($categories));
        //dd($categories);

        $articleForm = $this->createForm(
            CreateArticleFormType::class,
            $article,
            [
                'categories' => $categories
            ],
        );

        $articleForm->handleRequest($request);
        //dd($articleForm);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            //dd($articleForm->get('includes')->getData());

            $categories = new ArrayCollection($articleForm->get('includes')->getData());

            foreach ($categories as $category) {
                $article->addIncludes($category);
            }

            //dd($article->getIncludes());

            /* foreach (preg_split("/(?![a-zA-Z0-9_@#]+)/", $article->getContent(), -1, PREG_SPLIT_NO_EMPTY) as $word) {
                $word = preg_replace("/ /", "", $word);
                if (preg_match("/^#/", $word)) {
                    $tag = new Tags;
                    $tag->setName($word);
                    $article->addConcerns($tag);
                    //$em->persist($tag);
                } elseif (preg_match("/^@/", $word)) {
                    $word = substr($word, 1);
                    $mentionedUser = $this->em->getRepository(Users::class)->findOneBy(['username' => $word]);
                    if (!empty($mentionedUser)) {
                        $article->addMention($mentionedUser);
                    }
                }
            } */
            //dd($words);



            preg_match_all("/@\w+/", $article->getContent(), $mentionedUsers);
            //dd($mentionedUsers);

            //removing the @ with substr()
            //also $mentionedUsers[0] because $mentionedUsers looks like [ [ 'username1', 'username2', ... ] ]

            $mentionedUsers = array_map( function ($mentionedUser) {return substr((string)$mentionedUser, 1);}, $mentionedUsers[0]);
            $mentionedUsers = $this->em->getRepository(Users::class)->findBy(['username' => $mentionedUsers]);
            $article->setMentions(new ArrayCollection($mentionedUsers));
            
            /* foreach ($mentionedUsers[0] as $mentionedUser) {
                $mentionedUser = substr((string)$mentionedUser, 1);
                $mentionedUser = $this->em->getRepository(Users::class)->findOneBy(['username' => $mentionedUser]);
                if (!empty($mentionedUser)) {
                    $article->addMention($mentionedUser);
                }
            } */

            $article->setConcerns(new ArrayCollection());
            preg_match_all("/#\w+/", $article->getContent(), $matchedTags);
            foreach ($matchedTags[0] as $matchedTag) {
                $tag = new Tags();
                $tag->setName((string)$matchedTag);
                $article->addConcerns($tag);
            }



            $article->setUser($this->getUser());
            /*             $article->setReleaseDate(new \DateTime());
            $article->setContent($articleForm->get('content')->getData());
            $article->setTitle($articleForm->get('title')->getData()); */
            //$article->addIncludes($articleForm->get('categories')->getData());
            /* foreach($articleForm->get('tags')->getData() as $tag)
                $article->addConcerns($tag); */

            //$article->setDescription(substr($articleForm->get('content')->getData(), 0, 200));

            /* if($articleForm->get('mentions')->getData() != null)
                foreach($articleForm->get('mentions')->getData() as $user)
                    $article->addUser($user); */
            //dd($article);
            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
        }

        return $this->render('article/create.html.twig',  [
            'articleForm' => $articleForm,
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request)
    {

        $article = $this->em->getRepository(Articles::class)->find($id);

        if (!$this->getUser() OR $this->getUser()->getId() !== $article->getUser()->getId()) {
            return $this->redirectToRoute('app_home');
        }

        $categories = $this->em->getRepository(Categories::class)->findAll();
        //$categories = $em->getRepository(Categories::class)->findAllNames();
        //$categoriesNames = $af->flattenArray($categories);
        //$categories = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($categories));
        //dd($categories);

        $articleForm = $this->createForm(
            CreateArticleFormType::class,
            $article,
            [
                'categories' => $categories
            ],
        );

        $articleForm->handleRequest($request);
        //dd($articleForm);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            //dd($articleForm->get('includes')->getData());

            $categories = new ArrayCollection($articleForm->get('includes')->getData());

            //reset
            $article->setIncludes(new ArrayCollection());

            foreach ($categories as $category) {
                $article->addIncludes($category);
            }

            //dd($article->getIncludes());

            /* foreach (preg_split("/(?![a-zA-Z0-9_@#]+)/", $article->getContent(), -1, PREG_SPLIT_NO_EMPTY) as $word) {
                $word = preg_replace("/ /", "", $word);
                if (preg_match("/^#/", $word)) {
                    $tag = new Tags;
                    $tag->setName($word);
                    $article->addConcerns($tag);
                    //$em->persist($tag);
                } elseif (preg_match("/^@/", $word)) {
                    $word = substr($word, 1);
                    $mentionedUser = $this->em->getRepository(Users::class)->findOneBy(['username' => $word]);
                    if (!empty($mentionedUser)) {
                        $article->addMention($mentionedUser);
                    }
                }
            } */

            preg_match_all("/@\w+/", $article->getContent(), $mentionedUsers);
            //dd($mentionedUsers);

            //removing the @ with substr()
            //also $mentionedUsers[0] because $mentionedUsers looks like [ [ 'username1', 'username2', ... ] ]

            $mentionedUsers = array_map( function ($mentionedUser) {return substr((string)$mentionedUser, 1);}, $mentionedUsers[0]);
            $mentionedUsers = $this->em->getRepository(Users::class)->findBy(['username' => $mentionedUsers]);
            $article->setMentions(new ArrayCollection($mentionedUsers));
            
            /* foreach ($mentionedUsers[0] as $mentionedUser) {
                $mentionedUser = substr((string)$mentionedUser, 1);
                $mentionedUser = $this->em->getRepository(Users::class)->findOneBy(['username' => $mentionedUser]);
                if (!empty($mentionedUser)) {
                    $article->addMention($mentionedUser);
                }
            } */

            $article->setConcerns(new ArrayCollection());
            preg_match_all("/#\w+/", $article->getContent(), $matchedTags);
            foreach ($matchedTags[0] as $matchedTag) {
                $tag = new Tags();
                $tag->setName((string)$matchedTag);
                $article->addConcerns($tag);
            }
            //dd($tags);

            //dd($words);
            $article->setUser($this->getUser());
            /*             $article->setReleaseDate(new \DateTime());
            $article->setContent($articleForm->get('content')->getData());
            $article->setTitle($articleForm->get('title')->getData()); */
            //$article->addIncludes($articleForm->get('categories')->getData());
            /* foreach($articleForm->get('tags')->getData() as $tag)
                $article->addConcerns($tag); */

            //$article->setDescription(substr($articleForm->get('content')->getData(), 0, 200));

            /* if($articleForm->get('mentions')->getData() != null)
                foreach($articleForm->get('mentions')->getData() as $user)
                    $article->addUser($user); */
            //dd($article);
            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
        }

        return $this->render('article/create.html.twig',  [
            'articleForm' => $articleForm,
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/delete', name: 'app_article_delete', methods: ['POST'])]
    public function delete($id, Request $request)
    {
        if ($user_id = $this->getUser()->getId() and $request->request->get('_method') === 'DELETE') {
            $article = $this->em->getRepository(Articles::class)->find($id);
            if ($article and $article->getUser()->getId() === $user_id or in_array('Admin', $this->getUser()->getRoles())) {
                $this->em->remove($article);
                $this->em->flush();
            }
        }
        //dd($request->request->get('_method'));
        return $this->redirectToRoute('app_home');
    }
}

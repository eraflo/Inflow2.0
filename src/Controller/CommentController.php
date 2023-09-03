<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\MentionsReplacer;
use Doctrine\Common\Collections\ArrayCollection;

class CommentController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ) {

    }

    private function convertCommentObj(Comments $comment): object {

        $commentToSerialize = new class {

            public ?int $id = null;

            public ?string $content = null;

            public ?object $author = null;

            public ?int $from_article_id = null;

            public ?array $opinionSum = [];

            public ?array $mentions = [];

            public ?int $replies_to = null;
        };

        $author = new class {

            public ?int $id = null;

            public ?string $email = null;

            public ?string $username = null;

            public ?string $url = null;

            public ?int $follows;

            public ?int $followers;

            public $roles = [];
            
        };

        foreach ($comment->getMentions() as $mention) {

            $mentionToSerialize = new class {

                public ?int $id = null;
    
                public ?string $email = null;
    
                public ?string $username = null;
    
                public ?string $url = null;
    
                public ?int $follows;
    
                public ?int $followers;
    
                public $roles = [];
                
            };

            $mentionToSerialize->id = $mention->getId();
            $mentionToSerialize->email = $mention->getEmail();
            $mentionToSerialize->username = $mention->getUsername();
            $mentionToSerialize->url = $mention->getUrl();
            $mentionToSerialize->follows = $mention->getFollowsCount();
            $mentionToSerialize->followers = $mention->getFollowersCount();
            $mentionToSerialize->roles = $mention->getRoles();

            $commentToSerialize->mentions[] = $mentionToSerialize;

        }

        $author->id = $comment->getAuthor()->getId();
        $author->email = $comment->getAuthor()->getEmail();
        $author->username = $comment->getAuthor()->getUsername();
        $author->url = $comment->getAuthor()->getUrl();
        $author->follows = $comment->getAuthor()?->getFollowsCount();
        $author->followers = $comment->getAuthor()?->getFollowersCount();
        $author->roles = $comment->getAuthor()->getRoles();

        $commentToSerialize->id = $comment->getId();
        $commentToSerialize->content = $comment->getContent();
        $commentToSerialize->from_article_id = $comment->getFromArticle()->getId();
        $commentToSerialize->replies_to = $comment->getRepliesTo()?->getId();
        $commentToSerialize->author = $author;

        return $commentToSerialize;
    }

    #[Route('articles/{article_id}/comments/{comment_id}/replies', name: 'app_comment_replies', methods: ['GET'])]
    public function replies($article_id, $comment_id) {
        $replies = $this->em->getRepository(Comments::class)->findBy(['replies_to' => $comment_id]);
        $convertedReplies = [];

        foreach ($replies as $reply) {
            $reply = $this->convertCommentObj($reply);
            $convertedReplies[] = $reply;
        }

        /* $serializedResponse = $serializer->serialize(['replies' => $replies], 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]); */
        return $this->json($convertedReplies);
    }

    #[Route('articles/{article_id}/comments/add', name: 'app_comment_create', methods: ['POST'])]
    public function index(Request $request, SerializerInterface $serializer, $article_id): JsonResponse
    {
        //$requestData = $request->request?->getData();
        $user_id = $this->getUser()?->getId();
        if (!empty($user_id)) {
            $article = $this->em->getRepository(Articles::class)->find($article_id ?? 0);
            if ($article) {
                
                $comment = new Comments();
                $comment->setAuthor($this->getUser());
                $comment->setFromArticle($article);
                $comment->setContent($request->request->all()['comment']['content'] ?? "");
                $type = 'addition';

                if (!empty($repliesToId = $request->request->all()['comment']['repliesTo'] ?? NULL)) {
                    if ($repliesTo = $this->em->getRepository(Comments::class)->find($repliesToId) ?? NULL) {
                        if (!$repliesTo->getRepliesTo()) {
                            $comment->setRepliesTo($this->em->getRepository(Comments::class)->find((int)$request->request->all()['comment']['repliesTo']));
                            $type = 'reply';
                        } else {
                            return $this->json(['status' => 'error', 'message' => 'chaining replies isn\'t allowed']);
                        }
                    } else {
                        return $this->json(['status' => 'error', 'message' => 'cannot reply to a non-existing comment']);
                    }
                }

                $mentionedUsers = [];
                preg_match_all("/@\w+/", $comment->getContent(), $mentionedUsers);
                //var_dump($mentionedUsers);

                //removing the @ with substr()
                //also $mentionedUsers[0] because $mentionedUsers looks like [ [ 'username1', 'username2', ... ] ]

                $mentionedUsers = array_map( function ($mentionedUser) {return substr((string)$mentionedUser, 1);}, $mentionedUsers[0]);
                $mentionedUsers = $this->em->getRepository(Users::class)->findBy(['username' => $mentionedUsers]);
                $comment->setMentions(new ArrayCollection($mentionedUsers));

                $this->em->persist($comment);
                $this->em->flush();
                /* $serializedResponse = $serializer->serialize(['message' => $responseText, 'comment' => $comment, 'type' => $type], 'json', [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId();
                    },
                ]); */
                $convertedComment = $this->convertCommentObj($comment);
                return $this->json(['status' => 'success', 'message' => 'comment added', 'comment' => $convertedComment, 'type' => $type]);
            } else {
                $message = $request->request->all();
                
                return $this->json(['status' => 'error', 'message' => 'article not found']);
            }
        } else {
            return $this->json(['status' => 'error', 'message' => 'authentication needed']);
        }
    }

    #[Route('articles/{article_id}/comments/{comment_id}/edit', name: 'app_comment_edit', methods: ['POST'])]
    public function edit(Request $request, SerializerInterface $serializer, $article_id, $comment_id): JsonResponse
    {
        //$requestData = $request->request?->getData();
        $user_id = $this->getUser()?->getId();
        if (!empty($user_id)) {
            $comment = $this->em->getRepository(Comments::class)->find($comment_id ?? 0);
            $comment->setContent($request->request->all()['comment']['content']);
            if ($comment->getAuthor() === $this->getUser()) {

                preg_match_all("/@\w+/", $comment->getContent(), $mentionedUsers);

                //removing the @ with substr()
                //also $mentionedUsers[0] because $mentionedUsers looks like [ [ 'username1', 'username2', ... ] ]

                $mentionedUsers = array_map( function ($mentionedUser) {return substr((string)$mentionedUser, 1);}, $mentionedUsers[0]);
                $mentionedUsers = $this->em->getRepository(Users::class)->findBy(['username' => $mentionedUsers]);
                $comment->setMentions(new ArrayCollection($mentionedUsers));

                $this->em->persist($comment);
                $this->em->flush();
                /* $serializedResponse = $serializer->serialize(['message' => 'comment edited', 'comment' => $comment, 'type' => 'edition'], 'json', [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId(); // Replace this with a suitable way to identify the object
                    },
                ]); */
                $convertedComment = $this->convertCommentObj($comment);
                return $this->json(['status' => 'success', 'message' => 'comment edited', 'comment' => $convertedComment, 'type' => 'edition']);
            } else {
                //$message = $request->request->all();
                
                $response = $this->json(['status' => 'error', 'message' => 'you\'re not the original author of this comment!']);
            }
        } else {
            $response = $this->json(['status' => 'error', 'message' => 'authentication needed']);
        }
        return $response;
    }

    #[Route('articles/{article_id}/comments/{comment_id}/delete', name: 'app_comment_delete', methods: ['POST'])]
    public function delete($article_id, $comment_id): JsonResponse
    {
        //$requestData = $request->request?->getData();
        $user_id = $this->getUser()?->getId();
        if (!empty($user_id)) {
            $comment = $this->em->getRepository(Comments::class)->find($comment_id);
            if ($comment AND ($user_id === $comment->getAuthor()->getId() OR $this->isGranted('ROLE_ADMIN'))) {
                if ($comment->getFromArticle()->getId() === (int)$article_id) {
                    //$dependentComments = $this->em->getRepository(Comments::class)->findBy(['replies_to' => $comment->getId()]);
                    $this->em->remove($comment);
                    $this->em->flush();
                    $response = $this->json(['status' => 'success', 'message' => 'comment deleted', 'type' => 'deletion']);
                } else {
                    $response = $this->json(['status' => 'error', 'message' => 'this comment isn\'t linked to this article']);
                }
            } else {
                $response = $this->json(['status' => 'error', 'message' => 'either the comment does not exist or it\'s not your article']);
            }
        } else {
            $response = $this->json(['status' => 'error', 'message' => 'authentication needed']);
        }
        return $response;
    }
}

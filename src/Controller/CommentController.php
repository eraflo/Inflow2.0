<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CommentController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ) {

    }

    //in fact, it also handles comment editing since doctrine determines if the comment should be added or updated
    //because the id is also set if it is an edition 

    #[Route('articles/{article_id}/comments/add', name: 'app_comment_create', methods: ['POST'])]
    public function index(Request $request, SerializerInterface $serializer, $article_id): JsonResponse
    {
        //$requestData = $request->request?->getData();
        $user_id = $this->getUser()?->getId();
        if (!empty($user_id)) {
            $article = $this->em->getRepository(Articles::class)->find($article_id ?? 0);
            if ($article) {
                
                $comment = new Comments();
                $responseText = 'comment added';    
                $comment->setAuthor($this->getUser());
                $comment->setFromArticle($article);
                $type = 'addition';

                if (!empty($repliesToId = $request->request->all()['comment']['repliesTo'] ?? NULL)) {
                    if ($repliesTo = $this->em->getRepository(Comments::class)->find($repliesToId) ?? NULL) {
                        if (!$repliesTo->getRepliesTo()) {
                            $comment->setRepliesTo($this->em->getRepository(Comments::class)->find((int)$request->request->all()['comment']['repliesTo']));
                            $type = 'reply';
                        } else {
                            return $this->json(['message' => 'chaining replies isn\'t allowed']);
                        }
                    } else {
                        return $this->json(['message' => 'cannot reply to a non-existing comment']);
                    }
                }

                $comment->setContent($request->request->all()['comment']['content'] ?? "");
                $this->em->persist($comment);
                $this->em->flush();
                $serializedResponse = $serializer->serialize(['message' => $responseText, 'comment' => $comment, 'type' => $type], 'json', [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId(); // Replace this with a suitable way to identify the object
                    },
                ]);
                return $this->json($serializedResponse);
            } else {
                $message = $request->request->all();
                
                return $this->json(['message' => 'article not found']);
            }
        } else {
            return $this->json(['message' => 'authentication needed']);
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
                $this->em->persist($comment);
                $this->em->flush();
                $serializedResponse = $serializer->serialize(['message' => 'comment edited', 'comment' => $comment, 'type' => 'edition'], 'json', [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId(); // Replace this with a suitable way to identify the object
                    },
                ]);
                return $this->json($serializedResponse);
            } else {
                //$message = $request->request->all();
                
                $response = $this->json(['message' => 'you\'re not the original author of this comment!']);
            }
        } else {
            $response = $this->json(['message' => 'authentication needed']);
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
                    $response = $this->json(['message' => 'comment deleted', 'type' => 'deletion']);
                } else {
                    $response = $this->json(['message' => 'this comment isn\'t linked to this article']);
                }
            } else {
                $response = $this->json(['message' => 'either the comment does not exist or it\'s not your article']);
            }
        } else {
            $response = $this->json(['message' => 'authentication needed']);
        }
        return $response;
    }
}

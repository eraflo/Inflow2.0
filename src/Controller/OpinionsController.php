<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Opinions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OpinionsController extends AbstractController
{
    #[Route('/articles/{article_id}/opinions/add', name: 'app_article_opinions', methods: ['POST'])]
    public function addArticleOpinion(EntityManagerInterface $em, Request $request, $article_id): JsonResponse
    {
        //$opinion = $request->request->get('opinion');
        $opinion = new Opinions();
        $opinion_value = $request->request->get('opinion') ?? null;
        // $opinion->setOpinionValue($opinion_value);
        // if (!empty($opinion_value)) {
        //     $opinion->setOpinionValue($opinion_value);
        // }
        $opinion->setOpinionValue($opinion_value ?? 0);
        
        //$opinion_value ? $opinion->setOpinionValue($opinion_value ?? 0) : null;
        $article = $em->getRepository(Articles::class)->find($article_id ?? 0);

        // $opinion->setOpinionValue(1);
        // $article = $em->getRepository(Articles::class)->find(1);

        $opinion->setArticle($article);

        //$response = new JsonResponse(['data' => 'Opinion Added']);
        //dd($request);
        if ($article AND $article->getId() AND $opinion AND in_array($opinion->getOpinionValue(), [1, -1]) AND $user = $this->getUser() AND in_array((int) $opinion_value, [1, -1])) {
            $previousOpinion = $em->getRepository(Opinions::class)->findOneBy(['user' => $this->getUser(), 'article' => $article]);
            if ($previousOpinion) {
                if ($previousOpinion->getOpinionValue() !== (int) $opinion_value) {
                    $previousOpinion->setOpinionValue((int) $opinion_value);
                    $em->persist($previousOpinion);
                    $response = $this->json(['message' => 'Opinion Updated', 'update_display' => 'reverse', 'opinion_value' => $opinion_value]);
                } else {
                    $em->remove($previousOpinion);
                    $response = $this->json(['message' => 'Opinion Removed', 'update_display' => 'delete', 'opinion_value' => $opinion_value]);
                }
                //$previousOpinion = $em->getRepository(Opinions::class)->findAll();
                //dd($previousOpinion);
            } else {
                $opinion->setUser($user);
                $em->persist($opinion);
                $response = $this->json(['message' => 'Opinion Added', 'update_display' => 'add', 'opinion_value' => $opinion_value]);
            }
            $em->flush();
        } else {
            $response = $this->json(['message' => 'Bad Request', 'update_display' => 'error']);
        }
        return $response;
    }

    #[Route('/articles/{article_id}/comments/{comment_id}/opinions/add', name: 'app_comment_opinions', methods: ['POST'])]
    public function addCommentOpinion(EntityManagerInterface $em, Request $request, $article_id, $comment_id): JsonResponse
    {
        $opinion = new Opinions();
        $opinion_value = $request->request->get('opinion') ?? null;

        $opinion->setOpinionValue($opinion_value ?? 0);
        
        $comment = $em->getRepository(Comments::class)->find($comment_id ?? 0);

        $opinion->setComment($comment);

        //$response = new JsonResponse(['data' => 'Opinion Added']);
        //dd($request);
        if ($comment AND $comment->getId() AND $opinion AND in_array($opinion->getOpinionValue(), [1, -1]) AND $user = $this->getUser() AND in_array((int) $opinion_value, [1, -1])) {
            $previousOpinion = $em->getRepository(Opinions::class)->findOneBy(['user' => $this->getUser(), 'comment' => $comment]);
            if ($previousOpinion) {
                if ($previousOpinion->getOpinionValue() !== (int) $opinion_value) {
                    $previousOpinion->setOpinionValue((int) $opinion_value);
                    $em->persist($previousOpinion);
                    $response = $this->json(['message' => 'Opinion Updated', 'update_display' => 'reverse', 'opinion_value' => $opinion_value]);
                } else {
                    $em->remove($previousOpinion);
                    $response = $this->json(['message' => 'Opinion Removed', 'update_display' => 'delete', 'opinion_value' => $opinion_value]);
                }
                //$previousOpinion = $em->getRepository(Opinions::class)->findAll();
                //dd($previousOpinion);
            } else {
                $opinion->setUser($user);
                $em->persist($opinion);
                $response = $this->json(['message' => 'Opinion Added', 'update_display' => 'add', 'opinion_value' => $opinion_value]);
            }
            $em->flush();
        } else {
            $response = $this->json(['message' => 'Bad Request', 'update_display' => 'error']);
        }
        return $response;
    }
}

<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/comments', name: 'api_comment_')]
class CommentController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request, CommentRepository $commentRepository): JsonResponse
    {
        $postId = $request->query->get('postId');
        $sort = $request->query->get('sort', 'newest');
        $limit = $request->query->get('limit', 50);

        $queryBuilder = $commentRepository->createQueryBuilder('c')
            ->where('c.commentedPost = :postId')
            ->setParameter('postId', $postId)
            ->setMaxResults($limit);

        if ($sort === 'oldest') {
            $queryBuilder->orderBy('c.timestamp', 'ASC');
        } else {
            $queryBuilder->orderBy('c.timestamp', 'DESC');
        }

        $comments = $queryBuilder->getQuery()->getResult();

        return $this->json([
            'comments' => array_map(fn(Comment $comment) => $this->serializeComment($comment), $comments)
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, PostRepository $postRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['postId']) || !isset($data['content'])) {
                return $this->json(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
            }

            $post = $postRepository->find($data['postId']);
            if (!$post) {
                return $this->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
            }

            $comment = new Comment();
            $comment->setCommentedPost($post)
                ->setContent($data['content'])
                ->setUserC($this->getUser())
                ->setTimestamp(new \DateTimeImmutable());

            $errors = $this->validator->validate($comment);
            if (count($errors) > 0) {
                return $this->json(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->json(
                $this->serializeComment($comment),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->json(['error' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        CommentRepository $commentRepository,
        PostRepository $postRepository
    ): JsonResponse {
        try {
            // Fetch the comment entity from the database
            $comment = $commentRepository->find($id);
            if (!$comment) {
                return $this->json(['error' => 'Comment not found'], Response::HTTP_NOT_FOUND);
            }

            // Get the data from the request
            $data = json_decode($request->getContent(), true);

            // Update fields based on request data
            if (isset($data['content'])) {
                $comment->setContent($data['content']);
            }

            // Optional: update other fields, such as the post or timestamp
            if (isset($data['postId'])) {
                $post = $postRepository->find($data['postId']);
                if ($post) {
                    $comment->setCommentedPost($post);
                } else {
                    return $this->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
                }
            }

            // Validate the updated comment
            $errors = $this->validator->validate($comment);
            if (count($errors) > 0) {
                return $this->json(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            // Persist the updated comment
            $this->entityManager->flush();

            return $this->json(
                $this->serializeComment($comment),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(['error' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        CommentRepository $commentRepository
    ): JsonResponse {
        try {
            // Fetch the comment entity from the database
            $comment = $commentRepository->find($id);
            if (!$comment) {
                return $this->json(['error' => 'Comment not found'], Response::HTTP_NOT_FOUND);
            }

            // Remove the comment from the database
            $this->entityManager->remove($comment);
            $this->entityManager->flush();

            return $this->json(['message' => 'Comment deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    private function serializeComment(Comment $comment): array
    {
        return [
            'id' => $comment->getId(),
            'content' => $comment->getContent(),
            'timestamp' => $comment->getTimestamp() ? $comment->getTimestamp()->format(\DateTime::ATOM) : null,
            'user' => [
                'id' => $comment->getUserC()->getId(),
                'username' => $comment->getUserC()->getUsername(),
            ],
            'post' => [
                'id' => $comment->getCommentedPost()->getId()
            ]
        ];
    }
}

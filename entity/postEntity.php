<?php

namespace entity;

class postEntity
{
    private \DatabaseManager $databaseManager;
    private int $id;
    private string $content;
    private string $createdAt;
    private string|null $updatedAt;
    private userEntity $user;

    public function __construct(
        \DatabaseManager $databaseManager,
        int $id,
        string $content,
        string $createdAt,
        string|null $updatedAt,
        userEntity $user,
    )
    {
        $this->databaseManager = $databaseManager;
        $this->id = $id;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->user = $user;
    }
    public function getData(): array
    {
        return [
            "id" => $this->id,
            "content" => $this->content,
            "createdAt" => $this->createdAt,
            "updatedAt" => $this->updatedAt,
            "user" => [$this->user->loggedInUser[0]['mail'], $this->user->loggedInUser[0]['ID_Utilisateur']]
        ];
    }

    public function deletePost(): void
    {
        $this->databaseManager->insert(
            request: "DELETE FROM Post WHERE ID_Post = :postID",
            param: [
                "postID" => $this->id
            ]
        );
    }
}
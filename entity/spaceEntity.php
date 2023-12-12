<?php

namespace entity;


class spaceEntity
{
    private \DatabaseManager $databaseManager;
    private int $id;
    private string $title;
    private string $description;
    private userEntity $user;
    public array $postList = [];

    public function __construct(
        \DatabaseManager $databaseManager,
        int $id,
        string $title,
        string $description,
        userEntity $user
    )
    {
        $this->databaseManager = $databaseManager;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->getPostList();
    }

    public function getData(): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "user" => $this->user->loggedInUser[0]['mail']
        ];
    }

    public function addPost(
        string $comment,
        UserEntity $user
    ): void
    {
        $this->databaseManager->insert(
            request: "INSERT INTO Post (Content, CreatedAt, ID_Utilisateur, ID_Forum) VALUES (:content, NOW(), :userID, :forumID)",
            param: [
                "content" => $comment,
                "userID" => $user->loggedInUser[0]['ID_Utilisateur'],
                "forumID" => $this->id
            ]
        );
    }

    public function savePost(
        postEntity $post
    ): void
    {
        $this->postList[] = $post;
    }

    public function getPostList(): void
    {
        $postData = [];
        foreach ($this->postList as $post) {
            $postData[] = $post->getData();
        }

        $this->postList = $postData;
    }
}
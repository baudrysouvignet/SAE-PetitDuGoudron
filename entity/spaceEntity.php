<?php

namespace entity;


class spaceEntity
{
    private \DatabaseManager $databaseManager;
    private int|null $id;
    private string $title;
    private string $description;
    private userEntity $user;
    public array $postList = [];

    public function __construct(
        \DatabaseManager $databaseManager,
        int|null $id,
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
            "user" => $this->user->loggedInUser[0]['mail'],
            "userId" => $this->user->loggedInUser[0]['ID_Utilisateur']
        ];
    }

    /**
     * @return void
     *
     * Add a space in the database
     */
    public function saveSpace(): void
    {
        $this->databaseManager->insert(
            request: "INSERT INTO Space (title, description, ID_Utilisateur) VALUES (:title, :description, :userID)",
            param: [
                "title" => $this->title,
                "description" => $this->description,
                "userID" => $this->user->loggedInUser[0]['ID_Utilisateur']
            ]
        );
        $this->id = $this->databaseManager->lastInsertId();
    }

    public function deleteSpace(): void
    {
        foreach ($this->postList as $post) {
            $post->deletePost();
        }

        $this->databaseManager->insert(
            request: "DELETE FROM Space WHERE ID_Space = :spaceID",
            param: [
                "spaceID" => $this->id
            ]
        );
    }

    /**
     * @param string $comment
     * @param UserEntity $user
     * @return void
     *
     * This function is used to add a post on a space
     */
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

        $post = new postEntity(
            databaseManager: $this->databaseManager,
            id: $this->databaseManager->lastInsertId(),
            content: $comment,
            createdAt: date("Y-m-d"),
            updatedAt: null,
            user: $user
        );

        $this->savePost(
            post: $post
        );
    }

    /**
     * @param postEntity $post
     * @return void
     *
     * This function is used to save a post in the postList
     */
    public function savePost(
        postEntity $post
    ): void
    {
        $this->postList[] = $post;
    }

    /**
     * @return void
     *
     * This function is used to get the postList and save it in an array
     */
    public function getPostList(): void
    {
        $postData = [];
        foreach ($this->postList as $post) {
            $postData[] = $post->getData();
        }
    }

    /**
     * @param postEntity $post
     * @return void
     *
     * This function is used to delete a post
     */
    public function deletePost(
        postEntity $post
    ): void
    {
        $post->deletePost ();
        $this->postList = array_filter($this->postList, function($postList) use ($post) {
            return $postList->getData()['id'] != $post->getData()['id'];
        });
    }
}
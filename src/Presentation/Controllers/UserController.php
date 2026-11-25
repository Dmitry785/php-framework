<?php

namespace App\Presentation\Controllers;

use App\Application\UserService;
use App\Domain\User;
use App\Presentation\ViewRenderer;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Request;
use Framework\Routing\Response;

class UserController{
    public function __construct(
        private UserService $userService,
        private ViewRenderer $viewRenderer
    ){

    }
    #[Route("/")]
    public function index(): Response{
        return Response::html($this->viewRenderer->render("User/index", 
            ["users" => $this->userService->getAll()]));
    }
    #[Route("/users")]
    public function getAll(): Response{
        return Response::json($this->userService->getAll());
    }
    #[Route("/users/{id}")]
    public function getUser(int $id) : Response {
        return Response::json($this->userService->findById($id));
    }
    #[Route("/users", "POST")]
    public function store(Request $request) : Response {
        $user = new User($request->input());
        $user = $this->userService->create($user);
        return Response::json($user, 201);
    }
    #[Route("/users", "PUT")]
    public function update(Request $request) : Response {
        $user = new User($request->input());
        $user = $this->userService->update($user);
        return Response::json($user);
    }

    #[Route("/users/{id}", "DELETE")]
    public function delete(int $id)
    {
        $this->userService->deleteById($id);
        return Response::json(["status" => "ok"]);
    }
}
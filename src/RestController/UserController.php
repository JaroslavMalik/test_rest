<?php

namespace App\RestController;

use App\Entity\User;
use App\Entity\Role;
use App\DTO\UserDTO;
use App\Form\RegistrationFormType;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View; 

class UserController extends FOSRestController
{
    /**
     * Creates an User resource
     * @Rest\Post("/users", name="rest_create_user")
     * @param Request $request
     * @return View
     */
    public function postUserX(Request $request, UserPasswordEncoderInterface $passwordEncoder): View
    {
		$role = $this->getDoctrine()->getRepository(Role::class)->find($request->get('roleId'));
		if (!$role) {
			return View::create(
				'Role with id ' . $request->get('roleId') . ' does not exist!', 
				Response::HTTP_NOT_FOUND
			);
			//throw new EntityNotFoundException('Role with id '.$articleId.' does not exist!');
		}
		
        $user = new User();
		$user->setName($request->get('name'));
		$user->setEmail($request->get('email'));
		$user->setRole($role);
		$user->setPassword(
			$passwordEncoder->encodePassword(
				$user,
				$request->get('plainPassword')
			)
		);

		try {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();
		} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
			return View::create(
				'User with email "' . $request->get('email') . '" already exist!', 
				Response::HTTP_CONFLICT
			);
		}

        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create(
			new UserDTO(
				$user->getId(), 
				$user->getName(), 
				$user->getEmail(), 
				$user->getRole()->getId()
			), 
			Response::HTTP_CREATED
		);
    }
	
	/**
     * Retrieves an User resource
     * @Rest\Get("/users/{userId}")
     * @return View
     */
    public function getUserX(int $userId): View
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
//        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create(
			new UserDTO(
				$user->getId(), 
				$user->getName(), 
				$user->getEmail(), 
				$user->getRole()->getId()
			), 
			Response::HTTP_OK
		);
    }
	
    /**
     * Retrieves a collection of Users resource
     * @Rest\Get("/users")
     * @return View
     */
    public function getUsersX(): View
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
		$usersDTO = array();
		foreach ($users as $user) {
			$usersDTO[$user->getId()] = new UserDTO(
				$user->getId(), 
				$user->getName(), 
				$user->getEmail(), 
				$user->getRole()->getId()
			);
		}
        // In case our GET was a success we need to return a 200 HTTP OK response with the collection of article object
        return View::create($usersDTO, Response::HTTP_OK);
    }
}

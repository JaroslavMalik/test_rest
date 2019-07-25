<?php

namespace App\RestController;

use App\Entity\Role;
use App\DTO\RoleDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View; 

class RoleController extends FOSRestController
{
    /**
     * Retrieves a collection of Users resource
     * @Rest\Get("/roles", name="rest_roles")
     * @return View
     */
    public function getRolesX(): View
    {
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
		$rolesDTO = array();
		foreach ($roles as $role) {
			$rolesDTO[$role->getId()] = new RoleDTO(
				$role->getId(), 
				$role->getCode(), 
				$role->getName()
			);
		}
        // In case our GET was a success we need to return a 200 HTTP OK response with the collection of article object
        return View::create($rolesDTO, Response::HTTP_OK);
    }
}

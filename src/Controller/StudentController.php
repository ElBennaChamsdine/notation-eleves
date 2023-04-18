<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends AbstractController
{

    #[Route('/students', name: 'student_list', methods: 'GET')]
    public function list(StudentRepository $studentRepository): JsonResponse
    {
        try {

            $students = $studentRepository->findAlls();
            if (count($students) == 0) {
                return $this->json(array("success" => true, "message" => "empty", "students" => $students));
            }
            return $this->json(array("success" => true, "message" => "success", "students" => $students));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }

    #[Route('/students', name: 'student_create', methods: 'POST')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {

            $data = json_decode($request->getContent(), true);

            $student = new Student();
            if (!empty($data['firstName'])) {
                $student->setFirstName($data['firstName']);
            } else {
                return $this->json(array("success" => true, "message" => "empty firstName"));
            }
            if (!empty($data['lastName'])) {
                $student->setLastName($data['lastName']);
            } else {
                return $this->json(array("success" => true, "message" => "empty lastName"));
            }
            if (!empty($data['dateOfBirth'])) {
                $student->setDateOfBirth(new \DateTime($data['dateOfBirth']));
            } else {
                return $this->json(array("success" => true, "message" => "empty dateOfBirth"));
            }

            $entityManager->persist($student);
            $entityManager->flush();

            return $this->json(array("success" => true, "message" => "Student created successfully !", "student" => $student->toArray()));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }

    #[Route('/students/{studentId}', name: 'student_show', methods: 'GET')]
    public function show($studentId, StudentRepository $studentRepository): Response
    {
        try {

            $student = $studentRepository->find($studentId);
            if (!$student) {
                return $this->json(array("success" => true, "message" => "Student doesn't exist"));
            }
            return $this->json(array("success" => true, "message" => "success", "student" => $student->toArray()));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }

    #[Route('/students/{studentId}', name: 'student_update', methods: 'PUT')]
    public function update(Request $request, EntityManagerInterface $entityManager, $studentId, StudentRepository $studentRepository): Response
    {
        try {


            $student = $studentRepository->find($studentId);
            if (!$student) {
                return $this->json(array("success" => true, "message" => "Student doesn't exist"));
            }
            $data = json_decode($request->getContent(), true);

            if (empty($data['firstName'])) {
                return $this->json(array("success" => true, "message" => "empty firstName"));
            } else {
                $student->setFirstName($data['firstName']);
            }

            if (empty($data['lastName'])) {
                return $this->json(array("success" => true, "message" => "empty lastName"));
            } else {
                $student->setLastName($data['lastName']);
            }

            if (empty($data['dateOfBirth'])) {
                return $this->json(array("success" => true, "message" => "empty dateOfBirth"));
            } else {
                $student->setDateOfBirth(new \DateTime($data['dateOfBirth']));
            }

            $entityManager->persist($student);
            $entityManager->flush();

            return $this->json(array("success" => true, "message" => "Student updated successfully !", "student" => $student->toArray()));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }

    #[Route('/students/{studentId}', name: 'student_delete', methods: 'DELETE')]
    public function delete(EntityManagerInterface $entityManager, $studentId, StudentRepository $studentRepository): Response
    {
        try {
            $student = $studentRepository->find($studentId);
            if (!$student) {
                return $this->json(array("success" => true, "message" => "Student doesn't exist"));
            }
            $entityManager->remove($student);
            $entityManager->flush();

            return $this->json(array("success" => true, "message" => "Student deleted successfully !"));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }
}

<?php

namespace App\Controller;



use App\Entity\Grade;
use App\Entity\Student;
use App\Repository\GradeRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class GradeController extends AbstractController
{

    #[Route('/students/{studentId}/grades', name: 'grade_list', methods: 'GET')]
    public function list($studentId, StudentRepository $studentRepository): Response
    {
        try {
            $student = $studentRepository->find($studentId);
            if (!$student) {
                return $this->json(array("success" => true, "message" => "Student doesn't exist"));
            }

            $grades = $student->getGrades();
            if (count($grades) == 0) {
                return $this->json(array("success" => true, "message" => "empty", "grades" => $grades));
            }
            return $this->json(array("success" => true, "message" => "success", "grades" => $grades));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }


    #[Route('/students/{studentId}/grades', name: 'grade_create', methods: 'POST')]
    public function create(Request $request, EntityManagerInterface $entityManager, $studentId, StudentRepository $studentRepository, GradeRepository $gradeRepository): Response
    {

        try {
            $data = json_decode($request->getContent(), true);
            $student = $studentRepository->find($studentId);
            if (!$student) {
                return $this->json(array("success" => true, "message" => "Student doesn't exist"));
            }
            $grade = new Grade();

            if ($data['value'] && $data['value'] != "") {
                if ($data['value'] <= 20 && $data['value'] >= 0) {
                    $grade->setValue($data['value']);
                } else {
                    return $this->json(array("success" => true, "message" => "value should be between 0 and 20"));
                }
            } else {
                return $this->json(array("success" => true, "message" => "empty value"));
            }

            if ($data['subject'] && $data['subject'] != "") {
                $gradeExist = $gradeRepository->findByStudentAndSubject($studentId, $data['subject']);
                if (count($gradeExist) > 0) {
                    return $this->json(array("success" => true, "message" => "This student already has a grade on this subject"));
                } else {
                    $grade->setSubject($data['subject']);
                }
            } else {
                return $this->json(array("success" => true, "message" => "empty subject"));
            }

            $grade->setStudent($student);

            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->json($grade->toArray());
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }

    #[Route('/students/{studentId}/grades/average', name: 'grade_average', methods: 'GET')]
    public function average($studentId, GradeRepository $gradeRepository, StudentRepository $studentRepository): Response
    {

        try {
            $student = $studentRepository->find($studentId);
            if (!$student) {
                return $this->json(array("success" => true, "message" => "Student doesn't exist"));
            }
            $grades = $gradeRepository->findBy(['student' => $student]);
            $sum = array_reduce($grades, function ($carry, $grade) {
                return $carry + $grade->getValue();
            }, 0);
            $average = count($grades) > 0 ? $sum / count($grades) : null;
            if ($average == null) {
                return $this->json(array("success" => true, "message" => "Student has no grades"));
            }
            return $this->json(array("success" => true, "message" => "success", "average" => $average));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }

    #[Route('/students/grades/average', name: 'grades_average', methods: 'GET')]
    public function classAverage(GradeRepository $gradeRepository): Response
    {
        try {
            $grades = $gradeRepository->findAll();
            $sum = array_reduce($grades, function ($carry, $grade) {
                return $carry + $grade->getValue();
            }, 0);
            $average = count($grades) > 0 ? $sum / count($grades) : null;
            if ($average == null) {
                return $this->json(array("success" => true, "message" => "Student has no grades"));
            }
            return $this->json(array("success" => true, "message" => "success", "average" => $average));
        } catch (Exception $e) {
            return $this->json(array("success" => false, "message" => "error"));
        }
    }
}

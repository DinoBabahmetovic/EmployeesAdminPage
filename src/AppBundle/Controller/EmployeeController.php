<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Salary_history;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeeController extends Controller
{
    /**
     * @Route("/", name="employee_list")
     */
    public function listAction()
    {
        //$employees = $this->getDoctrine()->getRepository('AppBundle:Employee')->findAll();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Employee');
        $query = $repository->createQueryBuilder('e')->orderBy('e.startDate', 'DESC')->getQuery();
        $employees = $query->getResult();

        return $this->render('employee/index.html.twig', array('employees' => $employees));
    }

    /**
     * @Route("/employee/create", name="employee_create")
     */
    public function createAction(Request $request)
    {
        $employee = new Employee;

        $form = $this->createFormBuilder($employee)
        	->add('first_name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'placeholder' => 'e.g. John')))
        	->add('last_name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'placeholder' => 'e.g. Doe')))
        	->add('birth_date', DateType::class, array('widget' => 'single_text', 'html5' => false, 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'form-control input-inline datepicker', 'data-provide' => 'datepicker', 'style' => 'margin-bottom:15px', 'data-date-format' => 'dd-mm-yyyy', 'placeholder' => 'dd-mm-yyyy')))
        	->add('birth_country', CountryType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        	->add('job_position', ChoiceType::class, array('choices' => array('FE developer' => 'FE developer', 'BE developer' => 'BE developer', 'System administrator' => 'System administrator', 'Sale' => 'Sale', 'Marketing' => 'Marketing'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        	->add('salary', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'placeholder' => 'e.g. 1500.5')))
        	->add('start_date', DateType::class, array('widget' => 'single_text', 'html5' => false, 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'form-control input-inline datepicker', 'data-provide' => 'datepicker', 'style' => 'margin-bottom:15px', 'data-date-format' => 'dd-mm-yyyy', 'placeholder' => 'dd-mm-yyyy')))
        	->add('register', SubmitType::class, array('label' => 'Register Employee','attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px;float:right')))
        	->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
        	$first_name = $form['first_name']->getData();
        	$last_name = $form['last_name']->getData();
        	$birth_date = $form['birth_date']->getData();
        	$birth_country = $form['birth_country']->getData();
        	$job_position = $form['job_position']->getData();
        	$salary = $form['salary']->getData();
        	$start_date = $form['start_date']->getData();

        	$employee->setFirstName($first_name);
        	$employee->setLastName($last_name);
        	$employee->setBirthDate($birth_date);
        	$employee->setBirthCountry($birth_country);
        	$employee->setJobPosition($job_position);
        	$employee->setSalary($salary);
        	$employee->setStartDate($start_date);

        	$em = $this->getDoctrine()->getManager();
        	$em->persist($employee);
        	$em->flush();

        	$this->addFlash('notice', 'Employee Added!');

        	return $this->redirectToRoute('employee_list');
        	
        }

        return $this->render('employee/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/employee/edit/{id}", name="employee_edit")
     */
    public function editAction($id, Request $request)
    {
        $employee = $this->getDoctrine()->getRepository('AppBundle:Employee')->find($id);
        $msg = 'Employee Updated!';

        //saving the previous values of fields
        $birth_country_prev = $employee->getBirthCountry();
		$job_position_prev = $employee->getJobPosition();
		$start_date_prev = $employee->getStartDate();
		$first_name_prev = $employee->getFirstName();
		$last_name_prev = $employee->getLastName();
		$birth_date_prev = $employee->getBirthDate();
		$salary_prev = $employee->getSalary();

        $form = $this->createFormBuilder($employee)
        	->add('first_name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'placeholder' => 'e.g. John')))
        	->add('last_name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'placeholder' => 'e.g. Doe')))
        	->add('birth_date', DateType::class, array('widget' => 'single_text', 'html5' => false, 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'form-control input-inline datepicker', 'data-provide' => 'datepicker', 'style' => 'margin-bottom:15px', 'data-date-format' => 'dd-mm-yyyy', 'placeholder' => 'dd-mm-yyyy')))
        	->add('birth_country', CountryType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'disabled' => true)))
        	->add('job_position', ChoiceType::class, array('choices' => array('FE developer' => 'FE developer', 'BE developer' => 'BE developer', 'System administrator' => 'System administrator', 'Sale' => 'Sale', 'Marketing' => 'Marketing'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'disabled' => true)))
        	->add('salary', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px', 'placeholder' => 'e.g. 1500.5')))
        	->add('start_date', DateType::class, array('widget' => 'single_text', 'html5' => false, 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'form-control input-inline datepicker', 'data-provide' => 'datepicker', 'style' => 'margin-bottom:15px', 'data-date-format' => 'dd-mm-yyyy', 'placeholder' => 'dd-mm-yyyy', 'disabled' => true)))
        	->add('edit', SubmitType::class, array('label' => 'Update Employee','attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px;float:right;margin-left:15px')))
        	->add('end_contract', SubmitType::class, array('label' => 'End Contract','attr' => array('class' => 'btn btn-danger', 'style' => 'margin-bottom:15px;float:right')))
        	->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
        	//three fields that cannot be changed, they will have the same value
        	$birth_country = $birth_country_prev;
        	$job_position = $job_position_prev;
        	$start_date = $start_date_prev;

        	//if the user edits the employee these 4 fields can get new values
        	if ($form->get('edit')->isClicked()){

	        	$first_name = $form['first_name']->getData();
	        	$last_name = $form['last_name']->getData();
	        	$birth_date = $form['birth_date']->getData();
	        	$salary = $form['salary']->getData();
	        }
	        //if the user ends the contract of employee, these 4 fields remain the same
	        else if ($form->get('end_contract')->isClicked()){
	        	$first_name = $first_name_prev;
	        	$last_name = $last_name_prev;
	        	$birth_date = $birth_date_prev;
	        	$salary = $salary_prev;
        	}

	        	$em = $this->getDoctrine()->getManager();
	        	$employee = $em->getRepository('AppBundle:Employee')->find($id);

	        	$employee->setFirstName($first_name);
	        	$employee->setLastName($last_name);
	        	$employee->setBirthDate($birth_date);
	        	$employee->setBirthCountry($birth_country);
	        	$employee->setJobPosition($job_position);
	        	$employee->setSalary($salary);
	        	$employee->setStartDate($start_date);
	        	//if user ends the contract with employee, end date is updated
	        	if ($form->get('end_contract')->isClicked()) $employee->setEndDate(new\DateTime('now'));

	        	//in the case when salary is changed, save it to database for history of salaries
	        	if ($salary_prev != $salary){
	        		$salary_history_element = new Salary_history;
	        		$salary_history_element->setEmployeeId($id);
	        		$salary_history_element->setSalary($salary_prev);
	        		$salary_history_element->setEndDate(new\DateTime('now'));
	        		$em->persist($salary_history_element);
	        		$msg = 'Employee updated, Salary history updated!';
	        	}

	        	$em->flush();

	        	$this->addFlash('notice', $msg);
        	
        	return $this->redirectToRoute('employee_list');
        	
        }

        return $this->render('employee/edit.html.twig', array('employee' => $employee, 'form' => $form->createView()));
    }

    /**
     * @Route("/employee/delete/{id}", name="employee_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
	    $employee = $em->getRepository('AppBundle:Employee')->find($id);
	    $employee->setEndDate(new\DateTime('now'));
	    $em->flush();
		$this->addFlash('notice', 'Employee Updated!');

        return $this->redirectToRoute('employee_list');
        //return $this->render('employee/delete.html.twig');
    }
	
}
<?php

namespace AppBundle\Controller;

use Abbert\Datagrid\Column\ActionColumn;
use Abbert\Datagrid\Datagrid;
use Abbert\Datagrid\Datasource\DoctrineSource;
use AppBundle\Entity\Commendation;
use AppBundle\Entity\Infraction;
use AppBundle\Entity\Note;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EmployeesController
 * @package AppBundle\Controller
 * @Route("/employees")
 */
class EmployeesController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $employeeGrid = new Datagrid();
        $employeeSource = new DoctrineSource($this->getDoctrine()->getRepository('AppBundle:User'));

        $employeeGrid->setDatasource($employeeSource);

        $employeeGrid->addColumn("OOC Name", function($row) {
            return $row->getUsername();
        });
        $employeeGrid->addColumn("IC Name", function($row) {
            return $row->getName();
        });
        $employeeGrid->addColumn("Rank", function($row) {
            return $row->getRank();
        });
        $employeeGrid->addColumn("Divisions", function($row) {
            return $row->getDivisions();
        });
        $employeeGrid->addColumn('Actions', new ActionColumn(function ($row) {
            return array(
                array(
                    'label' => 'dg-icon:fa fa-pencil',
                    'href' => '/employees/view/' . $row->getId(),
                )
            );
        }));

        $search = $employeeGrid->getRequest()->get('search');
        if (!empty($search['value'])) {
            $employeeSource->getQueryBuilder()->andWhere(
                't.name LIKE :query OR t.username LIKE :query OR t.divisions LIKE :query OR t.rank LIKE :query'
            );
            $employeeSource->getQueryBuilder()->setParameter('query', '%' . $search['value'] . '%');
        }

        $grid = $employeeGrid->render();

        return $this->render('AppBundle:Employees:index.html.twig', array(
            'grid' => $grid
        ));
    }

    /**
     * @Route("/search/{term}")
     */
    public function ajaxsearchAction($term = "")
    {
        $em = $this->getDoctrine()->getManager();

        if ($term == "")
        {
            $results = $em->getRepository('AppBundle:User')->createQueryBuilder('t')
                ->select('t')
                ->orderBy('t.id', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        } else {
            $results = $em->getRepository('AppBundle:User')->createQueryBuilder('t')
                ->select('t')
                ->where("t.name LIKE '%" . $term . "%'")
                ->orderBy('t.id', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }

        $response = "";
        foreach ($results as $result)
        {
            $response .= "<tr><td>";
            if ($result->getEnabled() == false) {
                $response .= "<i class='fa fa-fw fa-lock'></i>";
            }
            $response .=  $result->getUsername();
            $response .= "</td>";
            $response .= "<td><a class='btn btn-primary btn-xs pull-right' href='/employees/view/" . $result->getId() . "'><i class='fa fa-fw fa-pencil'></i></a></td></tr>";
        }

        return new Response($response);
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        if (!$this->get("security.token_storage")->getToken()->getUser()->isPoliceTrainingOfficer()) {
            $this->denyAccessUnlessGranted('ROLE_COMMAND');
        }

        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('steamid', TextType::class, ['label' => 'Steam ID'])
            ->add('username', TextType::class)
            ->add("forum", TextType::class, ["label" => "Forum name"])
            ->add('name', TextType::class, ['label' => 'Full name'])
            ->add('password', PasswordType::class)
            ->add('email', TextType::class, ['required' => false])
            ->add('rank', TextType::class, ['data' => 'Senior Officer'])
            ->add('divisions', TextType::class, ['data' => 'Patrol'])
            ->add('enabled', CheckboxType::class)
            ->getForm();

        if ($form->handleRequest($request)->isValid() == true)
        {
            $password = $request->request->get("form")['password'];
            $password = $this->get('security.password_encoder')->encodePassword($user, $password);

            $user->setPassword($password);
            $user->setAdded(new \DateTime());
            $user->setViews(0);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_employees_view', ['id' => $user->getId()]);
        }

        return $this->render('AppBundle:Employees:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/view/{id}/{jumpto}")
     */
    public function viewAction(User $user, $jumpto = "main", Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $localUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->getId() != $localUser->getId())
        {
            $user->setViews($user->getViews() + 1);
            $em->flush();
        }

        $divisions = explode(",", $user->getDivisions());

        if ($localUser->hasRole("ROLE_COMMAND")) {
            $form = $this->createFormBuilder($user)
                ->add('steamid', TextType::class, ['label' => 'Steam ID'])
                ->add('username')
                ->add("forum", TextType::class, ["label" => "Forum name"])
                ->add('name', TextType::class, ['label' => 'Full name'])
                ->add('divisions', TextType::class, ['label' => 'Divisions (comma seperated)'])
                ->add('Rank')
                ->add('email', TextType::class, ['required' => false])
                ->add('administrator', CheckboxType::class, ['mapped' => false, 'required' => false])
                ->add('supervisor', CheckboxType::class, ['mapped' => false, 'required' => false])
                ->add('command', CheckboxType::class, ['mapped' => false, 'required' => false])
                ->add('enabled');
        } else {
            $form = $this->createFormBuilder($user)
                ->add('name', TextType::class, ['label' => 'Full name']);
        }
        $form = $form->getForm();

        if ($form->handleRequest($request)->isValid()) {
//            $this->denyAccessUnlessGranted("ROLE_COMMAND");
            if ($localUser->hasRole("ROLE_COMMAND")) {
                if (isset($request->request->get('form')['supervisor'])) {
                    $user->addRole("ROLE_SUPERVISOR");
                } else {
                    $user->removeRole("ROLE_SUPERVISOR");
                }

                if (isset($request->request->get('form')['administrator'])) {
                    $user->addRole("ROLE_ADMINISTRATOR");
                } else {
                    $user->removeRole("ROLE_ADMINISTRATOR");
                }

                if (isset($request->request->get('form')['command'])) {
                    $user->addRole("ROLE_COMMAND");
                } else {
                    $user->removeRole("ROLE_COMMAND");
                }
            }

            $em->flush();
        }

        return $this->render('AppBundle:Employees:view.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'divisions' => $divisions,
            'jumpto' => $jumpto
        ));
    }

    /**
     * @Route("/remove")
     */
    public function removeAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR');

        return $this->render('AppBundle:Employees:remove.html.twig', array(
            // ...
        ));
    }

}

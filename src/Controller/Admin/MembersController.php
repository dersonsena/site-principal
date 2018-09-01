<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use App\Entity\Partner;
use App\Form\MemberType;
use App\Form\PartnerType;
use App\Repository\MemberRepository;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class MembersController
 * @package App\Controller\Admin
 * @Route("/admin/members", name="admin_")
 */
class MembersController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="members_list")
     * @Template("/admin/members/index.html.twig")
     * @param Request $request
     * @param MemberRepository $memberRepository
     * @return array
     */
    public function index(Request $request, MemberRepository $memberRepository)
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class);
        $filters = $request->get('member', []);
        $dataProvider = $memberRepository->getDataProvider($filters);

        if (!empty($filters)) {
            $member->setName($filters['name']);
        }

        $form->setData($member);
        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($dataProvider));
        $pagerfanta->setMaxPerPage($this->getParameter('pagination')['per_page']);
        $pagerfanta->setCurrentPage($request->get('page', 1));
        
        return [
            'pager' => $pagerfanta,
            'members' => $pagerfanta->getCurrentPageResults(),
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/create", name="members_create", methods="GET|POST")
     * @Template("admin/members/create.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function create(Request $request)
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            $this->addFlash('success', 'Membro foi salvo com sucesso!');
            return $this->redirectToRoute('admin_members_list');
        }

        return [
            'member' => $member,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="members_show", methods="GET")
     * @Template("admin/members/show.html.twig")
     * @param Partner $partner
     * @return array
     */
    public function show(Partner $partner)
    {
        return ['member' => $partner];
    }

    /**
     * @Route("/{id}/edit", name="members_edit", methods="GET|POST")
     * @Template("admin/members/edit.html.twig")
     * @param Request $request
     * @param Member $member
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, Member $member)
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Membro foi atualizado com sucesso!');
            return $this->redirectToRoute('admin_members_list');
        }

        return [
            'member' => $member,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="members_delete", methods="DELETE")
     * @param Request $request
     * @param Member $member
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Member $member)
    {
        if ($this->isCsrfTokenValid('delete' . $member->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($member);
            $em->flush();
        }

        $this->addFlash('success', 'Membro foi deletado com sucesso!');
        return $this->redirectToRoute('admin_members_list');
    }
}

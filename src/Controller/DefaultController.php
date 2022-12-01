<?php

namespace App\Controller;

use App\Domain\Enum\SourcesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="serials_list")
     */
    public function indexAction(): Response
    {
        $links = [
            ['id' => 1,
                'link' => 'ya.com',
                'name' => 'Anime name',
                'type' => SourcesEnum::ANILIBRIA(),
                'updateAt' => time()],
            ['id' => 2,
                'link' => 'ya.com',
                'name' => 'Film name',
                'type' => SourcesEnum::RUTOR(),
                'updateAt' => time()],

        ];
        return $this->render('default/index.html.twig', [
            'links' => $links,
        ]);
    }

    /**
     * @Route("/serials/add/", name="serials_add",  methods={"GET"})
     */
    public function addSerialsAction(Request $request): Response
    {

    }

    /**
     * @Route("/serials/{id}/edit/", name="serials_edit",  methods={"GET"})
     */
    public function editSerialsAction(Request $request, int $id): Response
    {

    }

    /**
     * @Route("/serials/store/", name="serials_store",  methods={"POST"})
     */
    public function storeSerialsAction(Request $request): Response
    {
        return $this->redirectToRoute('serials_list');
    }

    /**
     * @Route("/serials/{id}/remove/", name="serials_remove",  methods={"POST"})
     */
    public function removeSerialsAction(Request $request, int $id): Response
    {
        return $this->redirectToRoute('serials_list');
    }
}

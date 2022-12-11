<?php

namespace App\Controller;

use App\Domain\DTO\SeriesDTO;
use App\Domain\Enum\SourcesEnum;
use App\Domain\Service\SerialService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    private SerialService $serialService;

    private SerializerInterface $serializer;

    public function __construct(
        SerialService $serialService,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        $this->serialService = $serialService;
    }

    /**
     * @Route("/", name="serials_list")
     */
    public function indexAction(): Response
    {
        $links = $this->serialService->getAllSerials();

        return $this->render('default/index.html.twig', [
            'links' => $links,
            'types' => SourcesEnum::toArray(),
        ]);
    }

    /**
     * @Route("/serials/{id}/edit/", name="serials_edit",  methods={"GET"})
     */
    public function editSerialsAction(Request $request, int $id): Response
    {
        $link = $this->serialService->findSerial($id);

        return $this->render('default/edit.html.twig', [
            'link' => $link,
            'types' => SourcesEnum::toArray(),
        ]);
    }

    /**
     * @Route("/serials/store/", name="serials_store",  methods={"POST"})
     */
    public function storeSerialsAction(Request $request): Response
    {
        try {
            /** @var SeriesDTO $dto */
            $dto = $this->serializer->deserialize(
                json_encode($request->request->all()),
                SeriesDTO::class,
                'json'
            );
            if (null !== $dto->id) {
                $this->serialService->updateSerial($dto);
            } else {
                $this->serialService->createSerial($dto);
            }
        } catch (\Throwable $e) {
            dd($e);
        }

        return $this->redirectToRoute('serials_list');
    }

    /**
     * @Route("/serials/{id}/remove/", name="serials_remove",  methods={"GET"})
     */
    public function removeSerialsAction(Request $request, int $id): Response
    {
        $this->serialService->removeSerial($id);

        return $this->redirectToRoute('serials_list');
    }
}

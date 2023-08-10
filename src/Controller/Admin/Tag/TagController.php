<?php

namespace App\Controller\Admin\Tag;

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/admin/tag/list', name: 'admin.tag.index')]
    public function index(TagRepository $tagRepository): Response
    {
        // Récuperons tous les tags de la table contact
        $tags = $tagRepository->findAll();
        
        // La methode render nous permet de nous positionner automatiquement dans le dossier templates
        // Et le controleur fournit les contacts à l'index
        return $this->render('pages/admin/tag/index.html.twig',[
            "tags" => $tags
        ]);
    }
    
        // Pour creer un nouveau tag
     #[Route('/admin/tag/create', name: 'admin.tag.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        //Creons le nouveau tag à inserer dans la table
        $tag = new Tag();

        //Créons le formulaire en se basant sur son type et sur le nouveau tag
        $form = $this->createForm(TagFormType::class, $tag);

        // Associons les données de la requete au formulaire
        $form->handleRequest($request);

        //Nous verrifions si le formulaire est soumis ET validé
        if ( $form->isSubmitted() && $form->isValid() )
        {
            // Demandons au manager de preparer le requete d'insertion du nouveau tag à la base de donnée
            $em->persist($tag);

            //Demandons au manager d'executer la requete
            $em->flush();

            //effectuons la redirection vers la route menant a la page des tag en nous affichant un message flash
            // puis arretons l'execution du script.
            $this->addFlash("success", "Le tag a été ajouté avec succès.");
            return $this->redirectToRoute('admin.tag.index');
        }
        // Le controleur demande au systeme de lui rendre le contenu de cette page
        return $this->render("pages/admin/tag/create.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/tag/{id}/edit', name: 'admin.tag.edit', methods: ['GET', 'PUT'])]
    public function edit(Tag $tag, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(TagFormType::class,$tag,
        [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $em->persist($tag);
            $em->flush();

            $this->addFlash("success", "Le tag a été modifiée avec succès.");
            return $this->redirectToRoute('admin.tag.index');
        }

        return $this->render("pages/admin/tag/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }

    //Pour supprimer
    #[Route('/admin/tag/{id}/delete', name: 'admin.tag.delete', methods: ['DELETE'])]
    public function delete(Tag $tag, Request $request, EntityManagerInterface $em) : Response
    {
        // Nous verrifion Si le jetons de sécurité généré par le systeme est identique à celui provenant du formulaire,
        if ( $this->isCsrfTokenValid("delete_tag_" . $tag->getId(), $request->request->get('csrf_token')) ) 
        {
            // Demandons au manager de preparer la requete de suppression dutag à la base de donnée
            $em->remove($tag);

            //Demandons au manager d'executer la requete
            $em->flush();

            //Generons un message flash qui confirme la suppression
            $this->addFlash("success", "Le tag a été supprimé.");
        }
        //effectuons la redirection vers la route menant a la page des tag 
        // puis arretons l'execution du script.
        return $this->redirectToRoute('admin.tag.index');
    }
    
    }
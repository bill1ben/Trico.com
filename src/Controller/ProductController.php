<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\Image;
use App\Entity\Product;
use App\Form\ColorType;
use App\Form\ProductType;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET","POST"})
     */
    public function index(ProductRepository $productRepository,
                          ColorRepository $color,
                          CategoryRepository $categoryRepository,
                          SubCategoryRepository $subCategoryRepository,
                          Request $request): Response
    {

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'colors' => $color->findAll(),
            'allCategory' => $categoryRepository->findAll(),
            'allSubCatego' => $subCategoryRepository->findAll()
        ]);
    }


    /**
     * @Route("/search", name="product_search_index", methods={"GET","POST"})
     */
    public function search(ProductRepository $productRepository,
                          ColorRepository $color,
                          CategoryRepository $categoryRepository,
                          SubCategoryRepository $subCategoryRepository,
                          Request $request): Response
    {

        $colorGet = $request->get('color');
        $categoryGet = $request->get('category');
        $subCategoryGet = $request->get('subCategory');

        $colorGetClone = $request->get('colorClone');
        $categoryGetClone = $request->get('categoryClone');
        $subCategoryGetClone = $request->get('subCategoryClone');


    if(isset($colorGet) && !empty($colorGet) )
    {
       $colorToSearch = $colorGet;
    }else{
        $colorToSearch = $colorGetClone;
    }

    if(isset($categoryGet) && !empty($categoryGet) )
        {
            $categoryToSearch = $categoryGet;
        }else{
            $categoryToSearch = $categoryGetClone;
        }

    if(isset($subCategoryGet) && !empty($subCategoryGet) )
        {
            $subCategoryToSearch = $subCategoryGet;
        }else{
        $subCategoryToSearch = $subCategoryGetClone;
        }


        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(
                [
                    'colors' => $colorToSearch,
                    'categories' => $categoryToSearch,
                    'subCategories' => $subCategoryToSearch
                ]
            ),
            'colors' => $color->findAll(),
            'allCategory' => $categoryRepository->findAll(),
            'allSubCatego' => $subCategoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/product/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $Slugify = new Slugify();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $imageCover = $form->get('image')->getData();
            $name = $form->get('title')->getData();

            $nameSlug = $Slugify->slugify($name);


            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $img->setStatus(true);
                $product->addImage($img);
            }
            if($imageCover){
                $fichier = md5(uniqid()) . '.' . $imageCover->guessExtension();

                // On copie le fichier dans le dossier uploads
                $imageCover->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $img->setStatus(false);
                $product->addImage($img);
            }

            $product->setSlugName($nameSlug);


            $entityManager = $this->getDoctrine()->getManager();
            $product->setAuthor($this->getUser());
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "le produit <strong> {$product->getTitle()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slugName}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $imageCover = $form->get('image')->getData();
            $Slugify = new Slugify();
            $name = $form->get('title')->getData();
            $nameSlug = $Slugify->slugify($name);

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $img->setStatus(true);
                $product->addImage($img);
            }
            if($imageCover){
                $fichier = md5(uniqid()) . '.' . $imageCover->guessExtension();

                // On copie le fichier dans le dossier uploads
                $imageCover->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $img->setStatus(false);
                $product->setSlugName($nameSlug);
                $product->addImage($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/supprime/image/{id}", name="annonces_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

}

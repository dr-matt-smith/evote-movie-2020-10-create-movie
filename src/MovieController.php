<?php


namespace Tudublin;


use Mattsmithdev\PdoCrudRepo\DatabaseTableRepository;

class MovieController
{
    const PATH_TO_TEMPLATES = __DIR__ . '/../templates';
    private $twig;
    private $movieRepository;

    public function __construct()
    {
        $this->twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(self::PATH_TO_TEMPLATES));
        $this->movieRepository = new MovieRepository();
    }

    public function listMovies()
    {
        $movies = $this->movieRepository->findAll();

        $template = 'list.html.twig';
        $args = [
            'movies' => $movies
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function delete()
    {
        $id = filter_input(INPUT_GET, 'id');
        $success = $this->movieRepository->delete($id);

        if($success){
            $this->listMovies();
        } else {
            $message = 'there was a problem trying to delete Movie with ID = ' . $id;
            $this->error($message);
        }
    }

    public function error($errorMessage)
    {
        $template = 'error.html.twig';
        $args = [
        'errorMessage' => $errorMessage
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function createForm()
    {
        $template = 'newMovieForm.html.twig';
        $args = [];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function processNewMovie()
    {
        $title = filter_input(INPUT_POST, 'title');
        $category = filter_input(INPUT_POST, 'category');
        $price = filter_input(INPUT_POST, 'price');

        $m = new Movie();
        $m->setTitle($title);
        $m->setCategory($category);
        $m->setPrice($price);
        $m->setNumVotes(0);
        $m->setVoteTotal(0);

        $this->movieRepository->create($m);

        $this->listMovies();
    }
}


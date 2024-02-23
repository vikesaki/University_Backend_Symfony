<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class MoviesController extends AbstractController
{

	public function createMovie(Connection $connection, Request $request): Response
	{
		$data = $request->request->all();

		// Assuming you have 'title' and 'genreID' in your request data
		$title = $data['title'];
		$genreID = $data['genreID'];

		$sql = 'INSERT INTO Movies (Title, GenreID) VALUES (?, ?)';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $title);
		$stmt->bindValue(2, $genreID);
		$stmt->execute();

		return new Response('', Response::HTTP_CREATED);
	}

	public function updateMovie(Connection $connection, Request $request, $id): Response
	{
		$data = $request->request->all();

		// Assuming you have 'title' and 'genreID' in your request data
		$title = $data['title'];
		$genreID = $data['genreID'];

		$sql = 'UPDATE Movies SET Title = ?, GenreID = ? WHERE MovieID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $title);
		$stmt->bindValue(2, $genreID);
		$stmt->bindValue(3, $id);
		$stmt->execute();

		return new Response('', Response::HTTP_NO_CONTENT);
	}

	public function fetchMovie(Connection $connection, $id): Response
	{
		$sql = 'SELECT * FROM Movies WHERE MovieID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $id);
		$result = $stmt->executeQuery();

		$movie = $result->fetchAssociative();

		return $this->json($movie);
	}

	public function getMovies(Connection $connection): Response
	{
		$sql = 'SELECT * FROM Movies';
		$stmt = $connection->prepare($sql);
		$result = $stmt->executeQuery();

		$movies = $result->fetchAllAssociative();

		return $this->json($movies);
	}

	public function deleteMovie(Connection $connection, $id): Response
	{
		$sql = 'DELETE FROM Movies WHERE MovieID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $id);
		$stmt->execute();

		return new Response('', Response::HTTP_NO_CONTENT);
	}

	public function fetchMovieGenres(Connection $connection, $id): Response
	{
		// Fetch the movie
		$sql = 'SELECT * FROM Movies WHERE MovieID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $id);
		$result = $stmt->executeQuery();
		$movie = $result->fetchAssociative();

		// If the movie doesn't exist, return a 404 response
		if (!$movie) {
			return new Response('Movie not found', Response::HTTP_NOT_FOUND);
		}

		// Fetch genres associated with the movie
		$sql = 'SELECT genres.GenreID, genres.GenreName FROM genres INNER JOIN movies ON genres.GenreID = movies.GenreID WHERE movies.MovieID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $id);
		$result = $stmt->executeQuery();
		$genres = $result->fetchAllAssociative();

		return $this->json($genres);
	}
}

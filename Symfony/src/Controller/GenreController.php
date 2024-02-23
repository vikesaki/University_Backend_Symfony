<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class GenreController extends AbstractController
{

	public function createGenre(Connection $connection, Request $request): Response
	{
		$data = $request->request->all();

		// Assuming you have 'genreName' in your request data
		$genreName = $data['genreName'];

		$sql = 'INSERT INTO Genres (GenreName) VALUES (?)';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $genreName);
		$stmt->execute();

		return new Response('', Response::HTTP_CREATED);
	}

	public function updateGenre(Connection $connection, Request $request, $id): Response
	{
		$data = $request->request->all();

		// Assuming you have 'genreName' in your request data
		$genreName = $data['genreName'];

		$sql = 'UPDATE Genres SET GenreName = ? WHERE GenreID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $genreName);
		$stmt->bindValue(2, $id);
		$stmt->execute();

		return new Response('', Response::HTTP_NO_CONTENT);
	}

	public function fetchGenre(Connection $connection, $id): Response
	{
		$sql = 'SELECT * FROM Genres WHERE GenreID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $id);
		$result = $stmt->executeQuery();

		$genre = $result->fetchAssociative();

		return $this->json($genre);
	}
	
	public function getGenres(Connection $connection): Response
	{
		$sql = 'SELECT * FROM Genres';
		$stmt = $connection->prepare($sql);
		$result = $stmt->executeQuery();

		$genres = $result->fetchAllAssociative();

		return $this->json($genres);
	}

	public function deleteGenre(Connection $connection, $id): Response
	{
		$sql = 'DELETE FROM Genres WHERE GenreID = ?';
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(1, $id);
		$stmt->execute();

		return new Response('', Response::HTTP_NO_CONTENT);
	}
}


<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Playlist.
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute une playlist à la base de données.
     * @param Playlist $playlist
     */
    public function add(Playlist $playlist): void
    {
        $this->getEntityManager()->persist($playlist);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une playlist de la base de données.
     * @param Playlist $playlist
     */
    public function remove(Playlist $playlist): void
    {
        $this->getEntityManager()->remove($playlist);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les playlists triées par nom.
     * @param string $ordre Ordre de tri (ASC/DESC)
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('p')
                        ->leftjoin('p.formations', 'f')
                        ->groupBy('p.id')
                        ->orderBy('p.name', $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Retourne toutes les playlists triées par nombre de formations.
     * @param string $ordre Ordre de tri (ASC/DESC)
     * @return Playlist[]
     */
    public function findAllOrderByFormationNb($ordre): array
    {
        return $this->createQueryBuilder('p')
                        ->orderBy('p.formation_nb', $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Recherche des playlists contenant une valeur dans un champ.
     * @param string $champ Champ de recherche
     * @param string $valeur Valeur à rechercher
     * @param string $table Table associée (optionnel)
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table): array
    {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        if ($table == "") {
            return $this->createQueryBuilder('p')
                            ->leftjoin('p.formations', 'f')
                            ->where('p.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy('p.id')
                            ->orderBy('p.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        } else {
            return $this->createQueryBuilder('p')
                            ->leftjoin('p.formations', 'f')
                            ->leftjoin('f.categories', 'c')
                            ->where('c.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy('p.id')
                            ->orderBy('p.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        }
    }
}

<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Formation.
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Ajoute une formation à la base de données.
     * @param Formation $formation
     */
    public function add(Formation $formation): void
    {
        $playlist = $formation->getPlaylist();

        if ($playlist !== null) {
            $playlist->setFormationNb($playlist->getFormationNb() + 1);
            $this->getEntityManager()->persist($playlist);
        }

        $this->getEntityManager()->persist($formation);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une formation de la base de données.
     * @param Formation $formation
     */
    public function remove(Formation $formation): void
    {
        $playlist = $formation->getPlaylist();

        if ($playlist !== null) {
            $playlist->setFormationNb($playlist->getFormationNb() - 1);
            $this->getEntityManager()->persist($playlist);
        }

        $this->getEntityManager()->remove($formation);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les formations triées sur un champ.
     * @param string $champ Champ de tri
     * @param string $ordre Ordre de tri (ASC/DESC)
     * @param string $table Table associée (optionnel)
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre, $table = ""): array
    {
        if ($table == "") {
            return $this->createQueryBuilder('f')
                    ->orderBy('f.' . $champ, $ordre)
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                    ->join('f.' . $table, 't')
                    ->orderBy('t.' . $champ, $ordre)
                    ->getQuery()
                    ->getResult();
        }
    }

    /**
     * Recherche des formations contenant une valeur dans un champ.
     * @param string $champ Champ de recherche
     * @param string $valeur Valeur à rechercher
     * @param string $table Table associée (optionnel)
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur, $table = ""): array
    {
        if ($valeur == "") {
            return $this->findAll();
        }
        if ($table == "") {
            return $this->createQueryBuilder('f')
                    ->where('f.' . $champ . ' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%' . $valeur . '%')
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                    ->join('f.' . $table, 't')
                    ->where('t.' . $champ . ' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%' . $valeur . '%')
                    ->getQuery()
                    ->getResult();
        }
    }
    
    /**
     * Retourne les n formations les plus récentes.
     * @param int $nb Nombre de formations à retourner
     * @return Formation[]
     */
    public function findAllLasted($nb): array
    {
        return $this->createQueryBuilder('f')
                ->orderBy('f.publishedAt', 'DESC')
                ->setMaxResults($nb)
                ->getQuery()
                ->getResult();
    }
    
    /**
     * Retourne la liste des formations d'une playlist.
     * @param int $idPlaylist ID de la playlist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array
    {
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('f.publishedAt', 'ASC')
                ->getQuery()
                ->getResult();
    }
    
    /**
     * Retourne les formations d'une playlist spécifique.
     * @param Playlist $playlist
     * @return array
     */
    public function findByPlaylist(Playlist $playlist): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.playlist = :playlist')
            ->setParameter('playlist', $playlist)
            ->getQuery()
            ->getResult();
    }
}

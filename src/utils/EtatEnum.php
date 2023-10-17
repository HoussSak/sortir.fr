<?php

namespace App\utils;

enum EtatEnum
{
    const CREEE = 'Créée';
    const OUVERTE = 'Ouverte';
    const CLOTUREE = 'Cloturée';
    const ANNULEE = 'Annulée';
    const PASSEE = 'Passée';
    const EN_COURS = 'En Cours';
    const ARCHIVEE = 'Archivée';
}
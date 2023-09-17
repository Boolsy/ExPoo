<?php

namespace classes;
/**
 * Que sont les espaces de noms ? Dans leur définition la plus large, ils représentent un moyen d'encapsuler des éléments.
 * Cela peut être conçu comme un concept abstrait, pour plusieurs raisons. Par exemple, dans un système de fichiers,
 * les dossiers représentent un groupe de fichiers associés et servent d'espace de noms pour les fichiers qu'ils contiennent.
 * Un exemple concret est que le fichier foo.txt peut exister dans les deux dossiers /home/greg et /home/other,
 * mais que les deux copies de foo.txt ne peuvent pas co-exister dans le même dossier.
 * De plus, pour accéder au fichier foo.txt depuis l'extérieur du dossier /home/greg,
 * il faut préciser le nom du dossier en utilisant un séparateur de dossier, tel que /home/greg/foo.txt.
 * Le même principe s'applique aux espaces de noms dans le monde de la programmation.
 *
 * Dans le monde PHP,
 * les espaces de noms sont conçus pour résoudre deux problèmes que rencontrent les auteurs de bibliothèques et d'applications lors de la réutilisation d'éléments tels que des classes ou des bibliothèques de fonctions :
 * - Collisions de noms entre le code que vous créez, les classes, fonctions ou constantes internes de PHP, ou celle de bibliothèques tierces.
 * - La capacité de faire des alias ou de raccourcir des Noms_Extremement_Long pour aider à la résolution du premier problème, et améliorer la lisibilité du code.
 *
 * Note: Les noms d'espaces de noms ne sont pas sensible à la casse.
 * Note: Les espaces de noms PHP (PHP\...) sont réservés pour l'utilisation interne du langage.
 */

/**
 * La class character est abstraite (abstract) afin de ne pas permettre son instanciation et forcer l'instanciation des classes héritées (dans cet exemple les classes pc et npc).
 * Si vous tentez d'instancier une classe abstraite, une erreur sera renvoyée.
 */
abstract class character
{
    public string $name = 'character';
    private int $id;
    private int $hp;
    private int $attack;
    private int $defense;
    private weapon $weapon;
    private magic $magic;

    /**
     * @param string $name
     * @param int $id
     */
    public function __construct(string $name, int $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * GETTERS
     */

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getHp(): int
    {
        return $this->hp;
    }

    public function getAttack(): int
    {
        return $this->attack;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getWeapon(): object
    {
        return $this->weapon;
    }

    public function getMagic(): object
    {
        return $this->magic;
    }

    /**
     * SETTERS
     */

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setHp(int $hp): void
    {
        $this->hp = $hp;
    }

    public function setAttack(int $attack): void
    {
        $this->attack = $attack;
    }

    public function setDefense(int $defense): void
    {
        $this->defense = $defense;
    }

    public function setWeapon(weapon $weapon): void
    {
        $this->weapon = $weapon;
    }

    public function giveWeapon(array $weapons): void
    {
        $this->weapon = $weapons[rand(0, count($weapons) - 1)];
    }

    public function giveMagic(array $magic): void
    {
        $this->magic = $magic[array_rand($magic)];
    }

    /**
     * @param object $target
     * @return string
     */



    public function calculateParryChance()
    {
        $attackPoints = $this->getAttack();
        $parryChance = min(100, $attackPoints / 10 * 10);
        return $parryChance;
    }

    public function calculateDodgeChance()
    {
        $defensePoints = $this->getDefense();
        $dodgeChance = min(100, $defensePoints / 10 * 10);
        return $dodgeChance;
    }

    public function parry(Character $attacker) //Gros bordel au niveau des dégats
    {
        $parryChance = $this->calculateParryChance();
        $randomNumber = rand(1, 100);

        if ($randomNumber <= $parryChance) {
            $counterAttackMessage = $this->counterAttack($attacker);
            return "Parade réussie ! " . $counterAttackMessage;
        } else {

            $damageToDefender = $attacker->getWeapon()->getDamage();


            $this->setHp($this->getHp() - $damageToDefender);

            $attackerName = $attacker->getName();
            $defenderName = $this->getName();

            return "Parade ratée ! " . "<br>"  . $attackerName . " inflige " . $damageToDefender . " points de dégâts à " .
                $defenderName . "<br>" . $defenderName . " a maintenant " . $this->getHp() . " points de vie restants,";
        }
    }



    public function counterAttack(Character $attacker) //Gros bordel au niveau des dégats aussi
    {
        $damageToAttacker = $this->getWeapon()->getDamage();
        $attacker->setHp($attacker->getHp() - $damageToAttacker);

        $attackerName = $attacker->getName();
        $defenderName = $this->getName();

        return "Contre-attaque ! " . $defenderName . " inflige " . $damageToAttacker . " points de dégâts à " .
            $attackerName . "!<br>" . $defenderName . " a maintenant " . $this->getHp() . " points de vie restants ";
    }






    public function dodge() // A bien fonctionné mais a mis son gilet jaune vers 17h et à bloquer le rond point toute
        // la soirée
    {
        $dodgeChance = $this->calculateDodgeChance();

        $randomNumber = rand(1, 100);

        if ($randomNumber <= $dodgeChance) {
            return "Esquive réussie !";
        } else {
            return false;
        }
    }


    public function Attack(object $target) : string
    {
        $parry = false;
        $dodge = false;
        $damage = 0;
        $def = 0;

        if ($this->getMagic()->category == capacity::CAT_OFFENSIVE) {
            // Magic attack
            $damage = $this->getMagic()->getDamage();
        } elseif ($this->getWeapon()->category == capacity::CAT_OFFENSIVE) {
            // Weapon attack
            $damage = $this->getWeapon()->getDamage();
            $def = $target->getDefense();

            // Parry?
            if ($this->getWeapon()->type == $this->getWeapon()::CAT_MELEE && $target->getWeapon()->category == capacity::CAT_DEFENSIVE) {
                $parry = true;
            }

            // Dodge?
            if ($target->getWeapon()->type == $target->getWeapon()::CAT_RANGED) {
                $dodge = true;
            }

            // Defensive magic bonus
            if ($target->getMagic()->category == capacity::CAT_DEFENSIVE) {
                $def += $target->getMagic()->getDefense();
            }
        } else {
            // Cancel attack
            return $this->name . ' échoue dans son attaque contre ' . $target->name . ' car il ne possède pas de capacité offensive !<br>';
        }

        // Ça touche?
        $def = $target->getDefense();
        $result = rand(1, $this->attack) - rand(1, $def);

        if ($result > 0) {
            // L'attaque touche
            // Calcul des dégâts
            $damage = $this->getWeapon()->getDamage();
            $live = $target->getHp() - $damage;
            $string = 'touché! ' . '<br>' . $target->name . ' est ';
            if ($live > 0) {
                $string .= 'blessé par ' . $this->getWeapon()->name . ' qui lui inflige ' . $damage . ' points de dégâts!' . '<br>' . 'Il lui reste ' . $live . ' points de vie';
            } else {
                $live = 0;
                $string .= 'mort!';
            }
            $target->setHp($live);

            // Vérifie si la cible réussit à parer ( pas convaincu car ca lance des parades réussie pas quand il ne
            // faut pas
            // faut que je revois la logique de la contre attaque et de la parade )
            if ($target->parry($this)) {
                $string .= "<br>" . $target->parry($this);
                $target->setHp($live);
            } elseif ($this->dodge($target)) {
                $string .= ' Esquivé';
            }
            } else {
            $string = 'Raté';
            }

        return $this->name . ' attaque ' . $target->name . ' avec une attaque de ' . $this->attack . ' face à une défense de ' . $def . '<br> Résultat : ' . $result . ' : ' . $string . '<br>';
    }

}


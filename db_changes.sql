#changes by leadsoft
ALTER TABLE `players` ADD `trustProgress` FLOAT NOT NULL AFTER `balance`;

/*
    sq10 | CI:B0104 | 1/5

    Modify the boss_combat_log table to include xp_awarded,
    which is going to be used in the bosses.php view.

    Default is 0 for combatibility with the old logs.
*/
ALTER TABLE `boss_combat_log` ADD `xp_awarded` INTEGER NOT NULL DEFAULT 0;
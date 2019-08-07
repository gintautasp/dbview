ALTER TABLE 
	`uzsakymai` 
	CHANGE `trukme_ruosimo` `trukme_ruosimo` INT UNSIGNED NULL DEFAULT NULL
	, CHANGE `trukme_kaitinimo` `trukme_kaitinimo` INT UNSIGNED NULL DEFAULT NULL
	;
	
-- kazkada seniau

ALTER TABLE 
	`uzsakymai` 
	ADD `busena` ENUM('anuliuotas','ivykdytas','uzsakytas') NOT NULL 
	DEFAULT 'uzsakytas' 
	AFTER `trukme_kaitinimo`
	;
	
CREATE TABLE `patiekalai` (
  `id` int(10) UNSIGNED NOT NULL,
  `pav` varchar(256) NOT NULL,
  `trukme_ruosimo` int(10) UNSIGNED DEFAULT NULL,
  `trukme_kaitinimo` int(10) UNSIGNED DEFAULT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	

-- id padarom primary

ALTER TABLE `patiekalai` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT

-- 2019 08 05

ALTER TABLE 
	`uzsakymai` ADD `laikas_uzsakymo` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `busena`
	, ADD `laikas_patekimo` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL AFTER `laikas_uzsakymo`
	, ADD `klientas` VARCHAR(255) NOT NULL AFTER `laikas_patekimo`
	, ADD `id_patiekalo` INT UNSIGNED NOT NULL AFTER `klientas`
	, ADD `kaina` DECIMAL(12,2) UNSIGNED NOT NULL AFTER `id_patiekalo`
;

ALTER TABLE `uzsakymai` CHANGE `id_patiekalo` `id_patiekalo` INT(10) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `patiekalai` ADD `kaina` DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT '0' AFTER `trukme_kaitinimo`;

INSERT INTO `patiekalai` (`pav`, `trukme_ruosimo`, `trukme_kaitinimo`)
SELECT 
	`pav`
    , MAX(`trukme_ruosimo`) AS `trukme_ruosimo`
    , MAX(`trukme_kaitinimo`) AS `trukme_kaitinimo` 
FROM 
	`uzsakymai` 
GROUP BY 
	`pav`
	
	
UPDATE	
	`uzsakymai`
LEFT JOIN
	`patiekalai` ON(
		`uzsakymai`.`pav`=`patiekalai`.`pav`
	)
SET
	`uzsakymai`.`id_patiekalo`=`patiekalai`.`id`

ALTER TABLE `uzsakymai` 
	ADD  FOREIGN KEY (`id_patiekalo`) REFERENCES `patiekalai`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
/*
	sugeneruojame kaina
*/
	
UPDATE `patiekalai` SET `kaina`=iFNULL(`trukme_ruosimo`,0)+IFNULL(`trukme_kaitinimo`,0)+`id`;
	
	

CREATE TABLE `qr_turn`.`users` (
	`id` INT NOT NULL AUTO_INCREMENT, 
	`username` VARCHAR(25) NOT NULL, 
	`password` BLOB NOT NULL, 
	`rol` INT NOT NULL, 
	PRIMARY KEY (`id`), 
	UNIQUE (`username`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE `qr_turn`.`status` (
	`id` INT NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(25) NOT NULL, 
	`description` VARCHAR(100) NULL,  
	PRIMARY KEY (`id`), 
	UNIQUE (`name`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE `qr_turn`.`sequences` (
	`sequence` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`date_reg` DATE NULL,
	`time_reg` TIME NULL, 
	`ip` VARCHAR(15) NULL,
	`turn` INT NULL,
	`date_scan_qr` DATE NULL, 
	`time_scan_qr` TIME NULL, 
	`id_user_atent` INT NULL,
	`date_atent` DATE NULL,
	`time_atent` TIME NULL, 
	`id_status` INT NULL, 
	PRIMARY KEY (`sequence`),
	CONSTRAINT `day_turn` UNIQUE (`date_scan_qr`, `turn`), 
	FOREIGN KEY (`id_user_atent`) REFERENCES `qr_turn`.`users`(`id`) ON UPDATE CASCADE, 
	FOREIGN KEY (`id_status`) REFERENCES `qr_turn`.`status`(`id`) ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

INSERT INTO `qr_turn`.`users` (`username`, `password`, `rol`) VALUES
('TABLE', AES_ENCRYPT('TABLE-QR', 'Papiro'), 1), 
('ESTACION-01', AES_ENCRYPT('ESTACION-01', 'Papiro'), 2),
('ESTACION-02', AES_ENCRYPT('ESTACION-02', 'Papiro'), 2),
('ESTACION-03', AES_ENCRYPT('ESTACION-03', 'Papiro'), 2),
('ESTACION-04', AES_ENCRYPT('ESTACION-04', 'Papiro'), 2),
('ESTACION-05', AES_ENCRYPT('ESTACION-05', 'Papiro'), 2),
('ESTACION-06', AES_ENCRYPT('ESTACION-06', 'Papiro'), 2);

INSERT INTO `qr_turn`.`status` (`name`, `description`) VALUES
('registered', 'Se creo el registro'), 
('assigned', 'El registro fue asignado'),
('closed', 'El registro fue cerrado');

INSERT INTO `qr_turn`.`sequences` (`date_reg`, `time_reg`, `id_status`) VALUES
(DATE(NOW()), TIME(NOW()), 1);

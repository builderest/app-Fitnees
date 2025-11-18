CREATE DATABASE IF NOT EXISTS u212136830_vidapro  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE u212136830_vidapro ;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    birthdate DATE NOT NULL,
    level VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(128) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(60) NOT NULL,
    level VARCHAR(30) NOT NULL,
    muscles VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    tips TEXT,
    duration INT DEFAULT 60,
    image VARCHAR(255) NOT NULL,
    animation_json TEXT,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rutinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(160) NOT NULL,
    goal VARCHAR(120) NOT NULL,
    level VARCHAR(40) NOT NULL,
    days_per_week TINYINT NOT NULL,
    description TEXT,
    is_public TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rutina_ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_id INT NOT NULL,
    ejercicio_id INT NOT NULL,
    position INT NOT NULL,
    series INT DEFAULT 3,
    repetitions VARCHAR(40) DEFAULT '12',
    duration INT DEFAULT 60,
    rest_seconds INT DEFAULT 30,
    FOREIGN KEY (rutina_id) REFERENCES rutinas(id) ON DELETE CASCADE,
    FOREIGN KEY (ejercicio_id) REFERENCES ejercicios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS entrenos_realizados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_id INT NOT NULL,
    user_id INT NOT NULL,
    duration_min INT DEFAULT 30,
    completed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rutina_id) REFERENCES rutinas(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS alimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    type VARCHAR(60) NOT NULL,
    calories INT NOT NULL,
    proteins DECIMAL(5,2) DEFAULT 0,
    carbs DECIMAL(5,2) DEFAULT 0,
    fats DECIMAL(5,2) DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comidas_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    type VARCHAR(40) NOT NULL,
    calories INT NOT NULL,
    proteins DECIMAL(5,2) DEFAULT 0,
    carbs DECIMAL(5,2) DEFAULT 0,
    fats DECIMAL(5,2) DEFAULT 0,
    eaten_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS metas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    main_goal VARCHAR(120) NOT NULL,
    current_weight DECIMAL(5,2),
    target_weight DECIMAL(5,2),
    weeks_target INT,
    daily_minutes INT,
    daily_steps INT,
    water_goal_ml INT,
    water_current_ml INT DEFAULT 0,
    workouts_per_week INT,
    progress_percent INT DEFAULT 0,
    calorie_target INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS progreso_peso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    logged_at DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS progreso_calorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    calories INT NOT NULL,
    logged_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name, email, password, gender, birthdate, level)
VALUES
('FitLife Coach', 'coach@fitlife.pro', '$2y$12$S2El/6.yYReBQlz50uFeA.mWhtGfDnmjNnCf6G9gIghpNLS/urr/C', 'masculino', '1990-01-01', 'intermedio')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO ejercicios (name, category, level, muscles, description, tips, duration, image, animation_json)
VALUES
('Sentadilla profunda', 'piernas', 'intermedio', 'cuádriceps, glúteos', 'Sentadilla con peso corporal controlada.', 'Mantén la espalda neutra y respira al subir.', 60, 'assets/images/ejercicios/squat.svg', '{"type":"pulse"}'),
('Plancha isométrica', 'core', 'basico', 'core, hombros', 'Plancha apoyando antebrazos.', 'Activa abdomen, no hundas la cadera.', 45, 'assets/images/ejercicios/plank.svg', '{"type":"glow"}'),
('Jumping Jacks', 'cardio', 'basico', 'full body', 'Saltos laterales coordinados.', 'Respira rítmicamente.', 40, 'assets/images/ejercicios/jumping-jacks.svg', '{"type":"wave"}'),
('Peso muerto rumano', 'espalda', 'avanzado', 'isquiotibiales, glúteos', 'Movimiento de bisagra con barra ligera.', 'Mantén barra pegada al cuerpo.', 50, 'assets/images/ejercicios/squat.svg', '{"type":"bar"}'),
('Press de pecho con mancuernas', 'pecho', 'intermedio', 'pectorales, tríceps', 'En banco plano, controla descenso.', 'Exhala al empujar.', 60, 'assets/images/ejercicios/squat.svg', '{"type":"expand"}'),
('Remo inclinado', 'espalda', 'intermedio', 'dorsal, romboides', 'Remo con barra ligera.', 'Saca pecho y aprieta escápulas.', 50, 'assets/images/ejercicios/plank.svg', '{"type":"slide"}'),
('Lunges alternados', 'piernas', 'basico', 'glúteos, femorales', 'Zancadas hacia adelante controladas.', 'Rodilla trasera casi toca el suelo.', 40, 'assets/images/ejercicios/squat.svg', '{"type":"loop"}'),
('Mountain climbers', 'cardio', 'intermedio', 'core, hombros', 'Escaladores rápidos.', 'No muevas la cadera en exceso.', 40, 'assets/images/ejercicios/jumping-jacks.svg', '{"type":"speed"}'),
('Burpees controlados', 'full body', 'avanzado', 'full body', 'Burpee sin salto explosivo.', 'Coordina respiración.', 45, 'assets/images/ejercicios/jumping-jacks.svg', '{"type":"impact"}'),
('Curl de bíceps', 'brazos', 'basico', 'bíceps', 'Curl con mancuernas.', 'Codos pegados al torso.', 45, 'assets/images/ejercicios/plank.svg', '{"type":"loop"}'),
('Elevaciones laterales', 'hombros', 'basico', 'deltoides medio', 'Sube mancuernas a la altura de hombro.', 'No balancees el cuerpo.', 40, 'assets/images/ejercicios/plank.svg', '{"type":"rise"}'),
('Puente de glúteo', 'piernas', 'basico', 'glúteos', 'Eleva cadera con control.', 'Aprieta glúteos arriba.', 50, 'assets/images/ejercicios/squat.svg', '{"type":"pulse"}'),
('Remo con banda', 'espalda', 'basico', 'dorsal', 'Usa banda elástica.', 'Mantén hombros lejos de las orejas.', 45, 'assets/images/ejercicios/plank.svg', '{"type":"wave"}'),
('Soga ligera', 'cardio', 'intermedio', 'full body', 'Salto con cuerda.', 'Mantén ritmo estable.', 60, 'assets/images/ejercicios/jumping-jacks.svg', '{"type":"jump"}'),
('Bicicleta estática', 'cardio', 'intermedio', 'piernas', 'Pedaleo moderado.', 'Mantén cadencia constante.', 120, 'assets/images/ejercicios/cycling.svg', '{"type":"spin"}');

INSERT INTO rutinas (user_id, name, goal, level, days_per_week, description, is_public)
VALUES
(NULL, 'Full body 3 días', 'Equilibrio general', 'principiante', 3, 'Circuito de cuerpo completo para comenzar.', 1),
(NULL, 'Hipertrofia 4 días', 'Ganar masa muscular', 'intermedio', 4, 'Rutina dividida para hipertrofia.', 1),
(NULL, 'Cardio quemagrasa', 'Pérdida de grasa', 'intermedio', 4, 'Sesiones cardio con fuerza ligera.', 1);

INSERT INTO rutina_ejercicios (rutina_id, ejercicio_id, position, series, repetitions, duration, rest_seconds)
VALUES
(1, 1, 1, 3, '12', 60, 30),
(1, 3, 2, 3, '40s', 40, 20),
(1, 2, 3, 3, '45s', 45, 20),
(2, 5, 1, 4, '10', 60, 45),
(2, 6, 2, 4, '12', 50, 30),
(2, 4, 3, 4, '8', 50, 45),
(3, 8, 1, 4, '40s', 40, 15),
(3, 9, 2, 4, '12', 45, 20),
(3, 14, 3, 4, '60s', 60, 30);

INSERT INTO alimentos (name, type, calories, proteins, carbs, fats)
VALUES
('Pechuga de pollo', 'proteína', 165, 31, 0, 4),
('Arroz integral', 'carbohidrato', 216, 5, 45, 2),
('Avena', 'carbohidrato', 150, 5, 27, 3),
('Batido verde', 'snack', 120, 3, 22, 2);

INSERT INTO metas (user_id, main_goal, current_weight, target_weight, weeks_target, daily_minutes, daily_steps, water_goal_ml, water_current_ml, workouts_per_week, progress_percent, calorie_target)
VALUES
(1, 'Perder grasa', 78, 70, 12, 45, 9000, 2500, 1200, 4, 25, 2100);

INSERT INTO progreso_peso (user_id, weight, logged_at) VALUES
(1, 78, '2024-01-01'),
(1, 77.2, '2024-01-15'),
(1, 76.4, '2024-02-01'),
(1, 75.8, '2024-02-15');

INSERT INTO progreso_calorias (user_id, calories, logged_date) VALUES
(1, 2050, '2024-02-10'),
(1, 2200, '2024-02-11'),
(1, 1980, '2024-02-12'),
(1, 2100, '2024-02-13');

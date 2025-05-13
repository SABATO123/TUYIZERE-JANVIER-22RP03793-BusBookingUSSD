-- Create database
CREATE DATABASE IF NOT EXISTS bus_booking;
USE bus_booking;

-- Create routes table
CREATE TABLE IF NOT EXISTS routes (
    route_id INT PRIMARY KEY AUTO_INCREMENT,
    from_location VARCHAR(100) NOT NULL,
    to_location VARCHAR(100) NOT NULL,
    departure_time TIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT NOT NULL
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    phone_number VARCHAR(15) NOT NULL,
    route_id INT NOT NULL,
    seat_number INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (route_id) REFERENCES routes(route_id)
);

-- Create seats table
CREATE TABLE IF NOT EXISTS seats (
    seat_id INT PRIMARY KEY AUTO_INCREMENT,
    route_id INT NOT NULL,
    seat_number INT NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (route_id) REFERENCES routes(route_id)
);

-- Insert sample routes for Rwanda
INSERT INTO routes (from_location, to_location, departure_time, price, available_seats) VALUES
('Kigali', 'Huye', '08:00:00', 2500.00, 30),
('Kigali', 'Musanze', '09:00:00', 3000.00, 30),
('Kigali', 'Rubavu', '10:00:00', 3500.00, 30),
('Nyanza', 'Nyamata', '11:00:00', 2000.00, 30),
('Kigali', 'Rusizi', '12:00:00', 5000.00, 30); 
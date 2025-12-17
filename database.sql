-- Shengyi Shi 744564
-- This script creates the database for the KXO205 Accommodation Booking System.
-- It includes tables for users, accommodations, and bookings.

CREATE DATABASE IF NOT EXISTS KXO205;
USE KXO205;

-- Drop existing tables (Order matters due to foreign keys)
DROP TABLE IF EXISTS BOOKING;
DROP TABLE IF EXISTS ACCOMMODATION;
DROP TABLE IF EXISTS USER;

-- Create USER table
CREATE TABLE USER (
    userId INT AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Stores hashed password
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    phoneNumber VARCHAR(20) NOT NULL,
    postalAddress VARCHAR(255) DEFAULT NULL,
    role ENUM('client', 'host', 'manager') NOT NULL DEFAULT 'client',
    abnNumber VARCHAR(50) DEFAULT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userId)
) ENGINE=InnoDB;

-- Create ACCOMMODATION table
CREATE TABLE ACCOMMODATION (
    accommodationId INT AUTO_INCREMENT,
    hostId INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(50) NOT NULL,
    pricePerNight DECIMAL(10, 2) NOT NULL,
    maxGuests INT NOT NULL,
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    description TEXT,
    imagePath VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (accommodationId),
    FOREIGN KEY (hostId) REFERENCES USER(userId) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Create AMENITY table
CREATE TABLE AMENITY (
    amenityId INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    icon VARCHAR(50), -- Bootstrap icon class
    description VARCHAR(255),
    PRIMARY KEY (amenityId)
) ENGINE=InnoDB;

-- Create ACCOMMODATION_AMENITY junction table
CREATE TABLE ACCOMMODATION_AMENITY (
    accommodationId INT NOT NULL,
    amenityId INT NOT NULL,
    PRIMARY KEY (accommodationId, amenityId),
    FOREIGN KEY (accommodationId) REFERENCES ACCOMMODATION(accommodationId) ON DELETE CASCADE,
    FOREIGN KEY (amenityId) REFERENCES AMENITY(amenityId) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Create BOOKING table
CREATE TABLE BOOKING (
    bookingId INT AUTO_INCREMENT,
    userId INT NOT NULL,
    accommodationId INT NOT NULL,
    checkInDate DATE NOT NULL,
    checkOutDate DATE NOT NULL,
    guests INT NOT NULL,
    phoneNumber VARCHAR(20) NOT NULL,
    totalPrice DECIMAL(10, 2) NOT NULL,
    paymentDetails TEXT NOT NULL, -- Encrypted payment info
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (bookingId),
    FOREIGN KEY (userId) REFERENCES USER(userId),
    FOREIGN KEY (accommodationId) REFERENCES ACCOMMODATION(accommodationId)
) ENGINE=InnoDB;

-- Insert Sample Data

-- Users
INSERT INTO USER (email, password, firstName, lastName, phoneNumber, postalAddress, role, abnNumber) VALUES
('manager@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'System', 'Manager', '0400000000', '1 Manager Street, Sydney NSW 2000', 'manager', NULL),
('host1@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'John', 'Host', '0411111111', '123 Ocean Drive, Sydney NSW 2026', 'host', '12345678901'),
('host2@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'Jane', 'Owner', '0422222222', '456 CBD Street, Melbourne VIC 3000', 'host', '98765432109'),
('host3@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'Ethan', 'Hills', '0455555555', '88 Peak Road, Hobart TAS 7000', 'host', '56789012345'),
('host4@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'Grace', 'Bay', '0466666666', '55 Marina Parade, Perth WA 6000', 'host', '21098765432'),
('client1@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'Alice', 'Traveler', '0433333333', '789 Travel Lane, Brisbane QLD 4000', 'client', NULL),
('client2@kxo205.com', '$2b$10$xniOLT3xwzGy.NDeU54wzeuqQxUHo9prBBYtoCigtVl.WpbnUmn1W', 'Bob', 'Guest', '0444444444', '101 Guest Road, Melbourne VIC 3001', 'client', NULL);

-- Amenities (设施)
INSERT INTO AMENITY (name, icon, description) VALUES
('Smoking Allowed', 'bi-tornado', 'Smoking is permitted in this property'),
('Garage', 'bi-car-front', 'Private garage or parking space'),
('Pet Friendly', 'bi-heart', 'Pets are welcome'),
('Internet', 'bi-wifi', 'High-speed internet/WiFi available'),
('Air Conditioning', 'bi-snow', 'Climate control air conditioning'),
('Swimming Pool', 'bi-water', 'Private or shared swimming pool'),
('Kitchen', 'bi-egg-fried', 'Full kitchen with appliances'),
('Washer/Dryer', 'bi-basket', 'In-unit laundry facilities');

-- Accommodations
INSERT INTO ACCOMMODATION (hostId, name, address, city, pricePerNight, maxGuests, bedrooms, bathrooms, description, imagePath) VALUES
(2, 'Beachfront Villa', '123 Ocean Drive', 'Sydney', 350.00, 8, 4, 3, 'Stunning beachfront villa with direct ocean access.', 'img/house1.avif'),
(2, 'City Center Luxury Apartment', '456 CBD Street', 'Melbourne', 220.00, 4, 2, 2, 'Modern apartment in the heart of the city.', 'img/house2.avif'),
(3, 'Cozy Mountain Cabin', '789 Mountain View', 'Blue Mountains', 180.00, 6, 3, 2, 'Escape to nature in this cozy cabin.', 'img/house3.avif'),
(3, 'Modern Family Home', '101 Suburbia Lane', 'Brisbane', 280.00, 10, 5, 4, 'Spacious home perfect for large families.', 'img/house4.avif'),
(2, 'Country Retreat', '222 Vineyard Rd', 'Barossa Valley', 150.00, 4, 2, 1, 'Relaxing retreat in the wine country.', 'img/house5.avif'),
(3, 'Ocean View Penthouse', '777 Gold Coast Blvd', 'Gold Coast', 450.00, 6, 3, 3, 'Luxury penthouse with panoramic views.', 'img/house6.avif'),
(4, 'Harbor Loft', '12 Salamanca Place', 'Hobart', 195.00, 3, 1, 1, 'Waterfront loft steps from cafes and markets.', 'img/house1.avif'),
(4, 'Heritage Terrace', '5 Battery Point Ln', 'Hobart', 240.00, 4, 2, 2, 'Restored terrace with courtyard, close to harbor.', 'img/house2.avif'),
(5, 'Riverside Townhouse', '18 Swan River Dr', 'Perth', 260.00, 5, 3, 2, 'Townhouse overlooking the river, great for families.', 'img/house3.avif'),
(5, 'CBD Studio Pod', '99 Murray Street', 'Perth', 140.00, 2, 1, 1, 'Compact studio pod perfect for work trips.', 'img/house4.avif'),
(2, 'Surfside Shack', '45 Bondi Road', 'Sydney', 175.00, 3, 1, 1, 'Casual surf shack minutes from the sand.', 'img/house5.avif'),
(3, 'Rainforest Retreat', '321 Hinterland Way', 'Sunshine Coast', 210.00, 4, 2, 2, 'Private retreat surrounded by rainforest trails.', 'img/house6.avif'),
(4, 'Wine Country Cottage', '14 Vineyard Loop', 'Barossa Valley', 185.00, 4, 2, 1, 'Cottage nestled among vines with fire pit.', 'img/house1.avif'),
(5, 'Skyline Apartment', '200 St Georges Tce', 'Perth', 310.00, 4, 2, 2, 'High-floor apartment with skyline views.', 'img/house2.avif');

-- Accommodation-Amenity Relationships
-- 1: Beachfront Villa (Garage, Pet Friendly, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(1, 2), (1, 3), (1, 4), (1, 6), (1, 7);
-- 2: City Center Luxury Apartment (Garage, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(2, 2), (2, 4), (2, 5), (2, 7);
-- 3: Cozy Mountain Cabin (Smoking Allowed, Pet Friendly)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(3, 1), (3, 3), (3, 7);
-- 4: Modern Family Home (Garage, Pet Friendly, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(4, 2), (4, 3), (4, 4), (4, 6), (4, 7), (4, 8);
-- 5: Country Retreat (Smoking Allowed, Garage)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(5, 1), (5, 2), (5, 7);
-- 6: Ocean View Penthouse (Garage, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(6, 2), (6, 4), (6, 5), (6, 6);
-- 7: Harbor Loft (Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(7, 4), (7, 7);
-- 8: Heritage Terrace (Pet Friendly, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(8, 3), (8, 4), (8, 7);
-- 9: Riverside Townhouse (Garage, Pet Friendly, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(9, 2), (9, 3), (9, 4), (9, 7), (9, 8);
-- 10: CBD Studio Pod (Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(10, 4), (10, 5);
-- 11: Surfside Shack (Smoking Allowed, Pet Friendly, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(11, 1), (11, 3), (11, 4);
-- 12: Rainforest Retreat (nothing - removed old features)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(12, 7);
-- 13: Wine Country Cottage (Garage, Pet Friendly)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(13, 2), (13, 3), (13, 7);
-- 14: Skyline Apartment (Garage, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(14, 2), (14, 4), (14, 5);

-- Bookings
INSERT INTO BOOKING (userId, accommodationId, checkInDate, checkOutDate, guests, phoneNumber, totalPrice, paymentDetails, status) VALUES
(4, 1, '2025-11-15', '2025-11-18', 4, '+61400111222', 1050.00, '1TyQxpkQ/KOD+tCp3ekK/YDaY3VDoDRZi/PsBqO8reGkIQ2tRUez/F7j0iBWxK4wrojr1/KmS4rvMg0dMB0SmC+/ACL6pxt/C8RafKCaQOwMv/KppcYooO+d9ekEDIBDcOfZuc3kDSJuNxM8Tb7wxECu9q7FO3wfcB4RqirTQGQ=', 'confirmed'),
(4, 2, '2025-12-24', '2025-12-28', 2, '+61400111222', 880.00, 'ZwRh8YMbRfEvFphBpIcatH8l9KXeTkpIQ2B7fJMpArzgZ8O0Vkt90DdlhLLHLEaQlly7pT9Rg2LVHuwtekQCkmK2gdz/dtxqubc46wsr7efSwlI0s61RocoBo5y7qXQAyi0s3RKz6pkgt1O0gH4r15fsS17ilewrPqzfK+jTGao=', 'confirmed'),
(5, 5, '2025-09-01', '2025-09-05', 2, '+61400333444', 600.00, 'uQVPA9bxddPbVncNeCzZYsx4Y9IWSgh8GzP1lc/R8oubOEkP2CXm/EGPnRn6r9HWm2ZvlEYILTTnalBw3WtMjSR7vnA05CjFxiJQJvwjyQvKnXc+50H1LazAa3eOYstrUZZAV46awPKN8tw1djht/xSEnt6Vth67n5Xg3p88ZyA=', 'confirmed'),
(4, 4, '2025-12-05', '2025-12-08', 5, '+61400111222', 840.00, 'P04ecbgzXZto6SiEA4W38wzPysEhlWPcf7XHtbjdgoyd/6kPymT2tDQzaLcL6CdRtJxUGDu2D+oVLa7zbDBJVZx6PRqU8z5z511DyLDWpuVjsIy/FyZ/lO9NuVHt1RwB9AUGxrhk7TzUmAIAuvt6JK/Rg3h8S2UM2anslwUOtIf5RShYOYEkBlHgTWjeB7IC', 'confirmed'),
(5, 6, '2025-12-10', '2025-12-13', 2, '+61400333444', 900.00, 'uUyK9OMDcLz2mnw8SKpsC+HWDBZUg5i1Wre3MZcNuyJJQXJo0EdQi0I82KOPdc1NksImtzpzQG6F1ggSxfqD28HN76FinM2H7PqsQq5FGYtc/fIzviOaI1ZXPeb1KZZRiUpLeYhMQrkU8jzt/6IwxPhz+qjKNFsau31bFOPInQA=', 'confirmed'),
(4, 3, '2025-12-15', '2025-12-18', 4, '+61400111222', 540.00, 'toCrEh94LWygvKoZHcf+g9zummZW+2nIvSHxygQUap50yh6MjaYCd1jUxwFShR3tRSR6iHXp8eV5Jlng23qtheNUKJXNyV4yUkf/9FYEy2KxQ5o+RLpHukn9bnbt1K0l2z/O5ZHJWIn1JUze3z1wHBX+DPSxbkAthbYhFodot5Y=', 'confirmed'),
(5, 2, '2025-12-27', '2025-12-30', 2, '+61400333444', 660.00, '5nUlO2o7UULtbTuawiZVG5ogEHE9ND1P0t1ubPJ6o21H+gINcpKd+MkahPX7W+4Gr8CXdPED9LV8f8iuxr6A+FSTj1pdSe8gfy++DESKNtgx6dyqDsuOuFh86hagjJl0V5vdRAt8nT7j/I4lW7zsw5y6MYAT2vp3ZPwDH6u30xo=', 'confirmed'),
(4, 7, '2026-01-05', '2026-01-08', 3, '+61400111222', 585.00, 'Jf2VkTFEPCWiuVHWqniyQ4jvolZH/6PnaILk87UQa4wq1Mmhg4GDgphjQBn7SgEqEyO6LLR0YpwcrQRLz6a3wAuLxfvBQdbnbsW1URNi54Z7d9IbN8AA/oXFKWAyJXfj/jj2rMN31dVtNMBy+jy/OklpNvODCh59PfHUMtTUjLs=', 'confirmed'),
(5, 8, '2026-01-12', '2026-01-15', 2, '+61400333444', 720.00, '1tF8Hm01dS8M069HOaCmqK7SwshoBCIy5nyw/94xHcuZdHMp9vBAMYiRGSmE44954GK058NNChRPRWOxfeOw8BAQOlmvKLHal4bhMHDylCMs7KmMy2uY4jc4F+OVtzcf8B3ZBYxCVnpAZ1mk8S1cq/Yfri2LB5ZqsvXesmlmhYA=', 'confirmed');

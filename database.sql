-- Shengyi Shi 744564
-- This script creates the database for the KXO205 Accommodation Booking System.
-- It includes tables for users, accommodations, and bookings.

CREATE DATABASE IF NOT EXISTS KXO205;
USE KXO205;

-- Drop existing tables (Order matters due to foreign keys)
DROP TABLE IF EXISTS BOOKING;
DROP TABLE IF EXISTS ACCOMMODATION_AMENITY;
DROP TABLE IF EXISTS ACCOMMODATION;
DROP TABLE IF EXISTS AMENITY;
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
(5, 'Skyline Apartment', '200 St Georges Tce', 'Perth', 310.00, 4, 2, 2, 'High-floor apartment with skyline views.', 'img/house2.avif'),
-- Adelaide properties
(2, 'Adelaide Hills Cottage', '77 Summit Road', 'Adelaide', 165.00, 4, 2, 1, 'Charming cottage in the Adelaide Hills with vineyard views.', 'img/house3.avif'),
(3, 'Glenelg Beachfront Apartment', '8 Jetty Road', 'Adelaide', 235.00, 4, 2, 2, 'Modern beachfront apartment near Glenelg Beach.', 'img/house4.avif'),
-- Canberra properties
(4, 'Parliament View Apartment', '15 National Circuit', 'Canberra', 205.00, 3, 2, 1, 'Stylish apartment with views of Parliament House.', 'img/house5.avif'),
(2, 'Canberra Lake House', '32 Lake Burley Griffin Dr', 'Canberra', 295.00, 6, 3, 2, 'Lakeside home perfect for exploring the capital.', 'img/house6.avif'),
-- Darwin properties
(5, 'Tropical Darwin Villa', '45 Mindil Beach Road', 'Darwin', 225.00, 5, 3, 2, 'Tropical villa close to Mindil Beach Sunset Market.', 'img/house1.avif'),
(3, 'Waterfront Studio', '12 Cullen Bay Marina', 'Darwin', 155.00, 2, 1, 1, 'Modern studio with marina views and pool access.', 'img/house2.avif'),
-- Cairns properties
(4, 'Rainforest Hideaway', '88 Kuranda Range Rd', 'Cairns', 190.00, 4, 2, 2, 'Secluded hideaway surrounded by tropical rainforest.', 'img/house3.avif'),
(2, 'Palm Cove Beach House', '22 Williams Esplanade', 'Cairns', 275.00, 6, 3, 2, 'Luxury beach house steps from Palm Cove.', 'img/house4.avif'),
-- Newcastle properties
(5, 'Newcastle Beach Loft', '5 Scott Street', 'Newcastle', 180.00, 3, 1, 1, 'Industrial-chic loft near Newcastle Beach.', 'img/house5.avif'),
(3, 'Hunter Valley Estate', '101 Wine Country Lane', 'Newcastle', 320.00, 8, 4, 3, 'Grand estate in the heart of Hunter Valley wine region.', 'img/house6.avif'),
-- Alice Springs properties
(4, 'Desert Oasis Retreat', '7 Red Centre Way', 'Alice Springs', 170.00, 4, 2, 1, 'Unique desert retreat with stunning outback views.', 'img/house1.avif'),
(2, 'MacDonnell Ranges Cabin', '33 West MacDonnell Drive', 'Alice Springs', 145.00, 3, 1, 1, 'Cozy cabin near the MacDonnell Ranges.', 'img/house2.avif'),
-- Townsville properties
(5, 'Castle Hill View House', '18 Strand Esplanade', 'Townsville', 200.00, 5, 3, 2, 'Family home with Castle Hill and ocean views.', 'img/house3.avif'),
(3, 'Magnetic Island Bungalow', '25 Picnic Bay Road', 'Townsville', 165.00, 4, 2, 1, 'Tropical bungalow on beautiful Magnetic Island.', 'img/house4.avif'),
-- Geelong properties
(2, 'Geelong Waterfront Apartment', '42 Eastern Beach Road', 'Geelong', 185.00, 3, 2, 1, 'Waterfront apartment with bay views.', 'img/house5.avif'),
(4, 'Bellarine Peninsula Villa', '56 Ocean Road', 'Geelong', 250.00, 6, 3, 2, 'Spacious villa on the Bellarine Peninsula.', 'img/house6.avif'),
-- New York properties
(2, 'Manhattan Penthouse', '5th Avenue', 'New York', 550.00, 4, 2, 2, 'Luxury penthouse in the heart of Manhattan with skyline views.', 'img/house1.avif'),
(3, 'Brooklyn Loft', '123 Williamsburg St', 'New York', 380.00, 4, 2, 1, 'Trendy industrial loft in vibrant Brooklyn.', 'img/house2.avif'),
-- London properties
(4, 'Notting Hill Townhouse', '45 Portobello Road', 'London', 425.00, 6, 3, 2, 'Charming Victorian townhouse in colorful Notting Hill.', 'img/house3.avif'),
(5, 'Thames View Apartment', '12 South Bank', 'London', 295.00, 3, 2, 1, 'Modern apartment overlooking the River Thames.', 'img/house4.avif'),
-- Paris properties
(2, 'Eiffel Tower Studio', '7 Rue de Grenelle', 'Paris', 320.00, 2, 1, 1, 'Romantic studio with stunning Eiffel Tower views.', 'img/house5.avif'),
(3, 'Montmartre Artist Loft', '18 Rue Lepic', 'Paris', 275.00, 3, 1, 1, 'Bohemian loft in the artistic Montmartre district.', 'img/house6.avif'),
-- Tokyo properties
(4, 'Shibuya Modern Apartment', '2-1 Dogenzaka', 'Tokyo', 285.00, 3, 2, 1, 'Sleek apartment in bustling Shibuya district.', 'img/house1.avif'),
(5, 'Traditional Asakusa House', '1-1 Asakusa', 'Tokyo', 240.00, 4, 2, 1, 'Traditional Japanese house near Senso-ji Temple.', 'img/house2.avif'),
-- Singapore properties
(2, 'Marina Bay Luxury Suite', '10 Bayfront Avenue', 'Singapore', 450.00, 4, 2, 2, 'Ultra-modern suite with Marina Bay Sands views.', 'img/house3.avif'),
(3, 'Sentosa Beach Villa', '8 Palawan Beach Walk', 'Singapore', 380.00, 6, 3, 2, 'Tropical beach villa on resort island Sentosa.', 'img/house4.avif'),
-- Dubai properties
(4, 'Downtown Dubai Penthouse', '1 Sheikh Mohammed bin Rashid Blvd', 'Dubai', 650.00, 6, 3, 3, 'Opulent penthouse overlooking Burj Khalifa.', 'img/house5.avif'),
(5, 'Palm Jumeirah Villa', '88 Palm Crescent', 'Dubai', 750.00, 8, 4, 4, 'Exclusive waterfront villa on the iconic Palm.', 'img/house6.avif'),
-- Hong Kong properties
(2, 'Victoria Peak Apartment', '15 Peak Road', 'Hong Kong', 420.00, 3, 2, 1, 'Premium apartment with breathtaking harbor views.', 'img/house1.avif'),
(3, 'Central District Loft', '22 Des Voeux Road', 'Hong Kong', 340.00, 2, 1, 1, 'Contemporary loft in the financial heart of the city.', 'img/house2.avif'),
-- Bangkok properties
(4, 'Sukhumvit Luxury Condo', '55 Sukhumvit Road', 'Bangkok', 180.00, 4, 2, 2, 'Modern condo with rooftop pool in prime location.', 'img/house3.avif'),
(5, 'Riverside Traditional Home', '12 Chao Phraya River Rd', 'Bangkok', 155.00, 4, 2, 1, 'Traditional Thai home along the scenic river.', 'img/house4.avif'),
-- Rome properties
(2, 'Colosseum View Apartment', '8 Via dei Fori Imperiali', 'Rome', 365.00, 4, 2, 2, 'Historic apartment with views of the ancient Colosseum.', 'img/house5.avif'),
(3, 'Trastevere Loft', '33 Via della Lungara', 'Rome', 285.00, 3, 2, 1, 'Cozy loft in charming Trastevere neighborhood.', 'img/house6.avif'),
-- Barcelona properties
(4, 'Gothic Quarter Apartment', '15 Carrer del Bisbe', 'Barcelona', 295.00, 4, 2, 1, 'Beautiful apartment in the medieval Gothic Quarter.', 'img/house1.avif'),
(5, 'Sagrada Familia Penthouse', '101 Carrer de Mallorca', 'Barcelona', 380.00, 5, 3, 2, 'Stunning penthouse near Gaudí masterpiece.', 'img/house2.avif'),
-- Amsterdam properties
(2, 'Canal House', '88 Prinsengracht', 'Amsterdam', 325.00, 4, 2, 2, 'Classic canal house with authentic Dutch charm.', 'img/house3.avif'),
(3, 'Jordaan District Loft', '22 Westerstraat', 'Amsterdam', 265.00, 3, 1, 1, 'Stylish loft in the trendy Jordaan district.', 'img/house4.avif'),
-- Seoul properties
(4, 'Gangnam Luxury Suite', '456 Gangnam-daero', 'Seoul', 290.00, 3, 2, 1, 'Contemporary suite in upscale Gangnam district.', 'img/house5.avif'),
(5, 'Hanok Traditional House', '11 Bukchon-ro', 'Seoul', 220.00, 4, 2, 1, 'Authentic Korean hanok in historic Bukchon village.', 'img/house6.avif'),
-- Shanghai properties
(2, 'The Bund Luxury Apartment', '88 Zhongshan East Road', 'Shanghai', 380.00, 4, 2, 2, 'Stunning Art Deco apartment overlooking the iconic Bund.', 'img/house1.avif'),
(3, 'French Concession Villa', '158 Fuxing Road', 'Shanghai', 420.00, 6, 3, 2, 'Historic villa in the charming French Concession area.', 'img/house2.avif'),
(4, 'Pudong Skyline Penthouse', '100 Century Avenue', 'Shanghai', 480.00, 5, 3, 2, 'Modern penthouse with Oriental Pearl Tower views.', 'img/house3.avif'),
(5, 'Jing\'an Temple Suite', '1788 Nanjing West Road', 'Shanghai', 320.00, 3, 2, 1, 'Contemporary suite near vibrant Jing\'an Temple area.', 'img/house4.avif'),
-- Beijing properties
(2, 'Forbidden City Courtyard', '22 Jingshan Front Street', 'Beijing', 360.00, 4, 2, 2, 'Traditional courtyard house near the Forbidden City.', 'img/house5.avif'),
(3, 'Sanlitun Modern Loft', '19 Sanlitun Road', 'Beijing', 295.00, 3, 2, 1, 'Trendy loft in Beijing''s entertainment district.', 'img/house6.avif'),
(4, 'Hutong Heritage House', '88 Nanluoguxiang', 'Beijing', 260.00, 4, 2, 1, 'Authentic hutong residence with traditional charm.', 'img/house1.avif'),
(5, 'CBD Tower Apartment', '1 Jianguomenwai Avenue', 'Beijing', 340.00, 3, 2, 2, 'High-rise apartment in the business district.', 'img/house2.avif');

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
-- 15: Adelaide Hills Cottage (Pet Friendly, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(15, 3), (15, 7);
-- 16: Glenelg Beachfront Apartment (Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(16, 4), (16, 5), (16, 7);
-- 17: Parliament View Apartment (Garage, Internet)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(17, 2), (17, 4), (17, 7);
-- 18: Canberra Lake House (Garage, Pet Friendly, Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(18, 2), (18, 3), (18, 4), (18, 7), (18, 8);
-- 19: Tropical Darwin Villa (Swimming Pool, Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(19, 4), (19, 5), (19, 6), (19, 7);
-- 20: Waterfront Studio (Internet, Swimming Pool)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(20, 4), (20, 6);
-- 21: Rainforest Hideaway (Pet Friendly, Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(21, 3), (21, 4), (21, 7);
-- 22: Palm Cove Beach House (Swimming Pool, Internet, Kitchen, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(22, 4), (22, 5), (22, 6), (22, 7);
-- 23: Newcastle Beach Loft (Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(23, 4), (23, 5), (23, 7);
-- 24: Hunter Valley Estate (Garage, Pet Friendly, Internet, Swimming Pool, Kitchen, Washer/Dryer)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(24, 2), (24, 3), (24, 4), (24, 6), (24, 7), (24, 8);
-- 25: Desert Oasis Retreat (Pet Friendly, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(25, 3), (25, 5), (25, 7);
-- 26: MacDonnell Ranges Cabin (Pet Friendly, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(26, 3), (26, 7);
-- 27: Castle Hill View House (Garage, Internet, Swimming Pool, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(27, 2), (27, 4), (27, 6), (27, 7), (27, 8);
-- 28: Magnetic Island Bungalow (Pet Friendly, Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(28, 3), (28, 4), (28, 7);
-- 29: Geelong Waterfront Apartment (Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(29, 4), (29, 5), (29, 7);
-- 30: Bellarine Peninsula Villa (Garage, Pet Friendly, Internet, Swimming Pool, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(30, 2), (30, 3), (30, 4), (30, 6), (30, 7);
-- 31: Manhattan Penthouse (Garage, Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(31, 2), (31, 4), (31, 5), (31, 7);
-- 32: Brooklyn Loft (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(32, 4), (32, 5), (32, 7);
-- 33: Notting Hill Townhouse (Pet Friendly, Internet, Kitchen, Washer/Dryer)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(33, 3), (33, 4), (33, 7), (33, 8);
-- 34: Thames View Apartment (Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(34, 4), (34, 5), (34, 7);
-- 35: Eiffel Tower Studio (Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(35, 4), (35, 5);
-- 36: Montmartre Artist Loft (Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(36, 4), (36, 7);
-- 37: Shibuya Modern Apartment (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(37, 4), (37, 5), (37, 7);
-- 38: Traditional Asakusa House (Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(38, 4), (38, 7);
-- 39: Marina Bay Luxury Suite (Garage, Internet, Air Conditioning, Swimming Pool)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(39, 2), (39, 4), (39, 5), (39, 6);
-- 40: Sentosa Beach Villa (Internet, Air Conditioning, Swimming Pool, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(40, 4), (40, 5), (40, 6), (40, 7);
-- 41: Downtown Dubai Penthouse (Garage, Internet, Air Conditioning, Swimming Pool, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(41, 2), (41, 4), (41, 5), (41, 6), (41, 7);
-- 42: Palm Jumeirah Villa (Garage, Internet, Air Conditioning, Swimming Pool, Kitchen, Washer/Dryer)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(42, 2), (42, 4), (42, 5), (42, 6), (42, 7), (42, 8);
-- 43: Victoria Peak Apartment (Internet, Air Conditioning)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(43, 4), (43, 5), (43, 7);
-- 44: Central District Loft (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(44, 4), (44, 5), (44, 7);
-- 45: Sukhumvit Luxury Condo (Internet, Air Conditioning, Swimming Pool, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(45, 4), (45, 5), (45, 6), (45, 7);
-- 46: Riverside Traditional Home (Pet Friendly, Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(46, 3), (46, 4), (46, 7);
-- 47: Colosseum View Apartment (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(47, 4), (47, 5), (47, 7);
-- 48: Trastevere Loft (Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(48, 4), (48, 7);
-- 49: Gothic Quarter Apartment (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(49, 4), (49, 5), (49, 7);
-- 50: Sagrada Familia Penthouse (Internet, Air Conditioning, Kitchen, Washer/Dryer)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(50, 4), (50, 5), (50, 7), (50, 8);
-- 51: Canal House (Pet Friendly, Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(51, 3), (51, 4), (51, 7);
-- 52: Jordaan District Loft (Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(52, 4), (52, 7);
-- 53: Gangnam Luxury Suite (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(53, 4), (53, 5), (53, 7);
-- 54: Hanok Traditional House (Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(54, 4), (54, 7);
-- 55: The Bund Luxury Apartment (Garage, Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(55, 2), (55, 4), (55, 5), (55, 7);
-- 56: French Concession Villa (Garage, Pet Friendly, Internet, Air Conditioning, Kitchen, Washer/Dryer)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(56, 2), (56, 3), (56, 4), (56, 5), (56, 7), (56, 8);
-- 57: Pudong Skyline Penthouse (Garage, Internet, Air Conditioning, Swimming Pool, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(57, 2), (57, 4), (57, 5), (57, 6), (57, 7);
-- 58: Jing'an Temple Suite (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(58, 4), (58, 5), (58, 7);
-- 59: Forbidden City Courtyard (Pet Friendly, Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(59, 3), (59, 4), (59, 7);
-- 60: Sanlitun Modern Loft (Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(60, 4), (60, 5), (60, 7);
-- 61: Hutong Heritage House (Internet, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(61, 4), (61, 7);
-- 62: CBD Tower Apartment (Garage, Internet, Air Conditioning, Kitchen)
INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES
(62, 2), (62, 4), (62, 5), (62, 7);

-- Bookings
INSERT INTO BOOKING (userId, accommodationId, checkInDate, checkOutDate, guests, phoneNumber, totalPrice, paymentDetails, status) VALUES
(4, 1, '2025-11-15', '2025-11-18', 4, '+61400111222', 1050.00, 'TEPm45YirWlswoJxcHXE8nVjcGxnZ1k4QnBNaVpvVS9CWHJ5cC92LzhDdzlZSzNWZFBaZlVQRklkbVBMVXQzZEFXTWcxV1U1MGlJa0w2MWRGd3ovZTZEVXBWb0VpMTFudDZMQVdvVkdsZW1mbW1LSCtISHZoa2RqZ2t1YldpNFluNVI4Z1JuN1VDeFFtb1JmRTY2aS9YTEdycGdTaGJKZmd0Yzd1QT09', 'confirmed'),
(4, 2, '2025-12-24', '2025-12-28', 2, '+61400111222', 880.00, 'TEPm45YirWlswoJxcHXE8nVjcGxnZ1k4QnBNaVpvVS9CWHJ5cC92LzhDdzlZSzNWZFBaZlVQRklkbVBMVXQzZEFXTWcxV1U1MGlJa0w2MWRGd3ovZTZEVXBWb0VpMTFudDZMQVdvVkdsZW1mbW1LSCtISHZoa2RqZ2t1YldpNFluNVI4Z1JuN1VDeFFtb1JmRTY2aS9YTEdycGdTaGJKZmd0Yzd1QT09', 'confirmed'),
(5, 5, '2025-09-01', '2025-09-05', 2, '+61400333444', 600.00, 'TEPm45YirWlswoJxcHXE8nVjcGxnZ1k4QnBNaVpvVS9CWHJ5cC92LzhDdzlZSzNWZFBaZlVQRklkbVBMVXQzZEFXTWcxV1U1MGlJa0w2MWRGd3ovZTZEVXBWb0VpMTFudDZMQVdvVkdsZW1mbW1LSCtISHZoa2RqZ2t1YldpNFluNVI4Z1JuN1VDeFFtb1JmRTY2aS9YTEdycGdTaGJKZmd0Yzd1QT09', 'confirmed'),
(4, 4, '2025-12-05', '2025-12-08', 5, '+61400111222', 840.00, '141TVCoac9W0lj2dqioVnExORmlXNGYwcEVKSVVTRjFoN29jNW5sdzhaSzYvQWcweGVjWk43QjliOFMzaXloUTgrZy9PNVdWSjRMU0FKVzUwY0tZUkl2QkNiSEZuNnVQYUdBYmpwcm9NbmM1eXJLYTd1eURWUEVVQkZsME1rWjFxNUlMZmlxNnQzRVBBQVNFOFJ6RU9UdEdhNHk4L29ORC96V1RPQT09', 'confirmed'),
(5, 6, '2025-12-10', '2025-12-13', 2, '+61400333444', 900.00, 'TEPm45YirWlswoJxcHXE8nVjcGxnZ1k4QnBNaVpvVS9CWHJ5cC92LzhDdzlZSzNWZFBaZlVQRklkbVBMVXQzZEFXTWcxV1U1MGlJa0w2MWRGd3ovZTZEVXBWb0VpMTFudDZMQVdvVkdsZW1mbW1LSCtISHZoa2RqZ2t1YldpNFluNVI4Z1JuN1VDeFFtb1JmRTY2aS9YTEdycGdTaGJKZmd0Yzd1QT09', 'confirmed'),
(4, 3, '2025-12-15', '2025-12-18', 4, '+61400111222', 540.00, '141TVCoac9W0lj2dqioVnExORmlXNGYwcEVKSVVTRjFoN29jNW5sdzhaSzYvQWcweGVjWk43QjliOFMzaXloUTgrZy9PNVdWSjRMU0FKVzUwY0tZUkl2QkNiSEZuNnVQYUdBYmpwcm9NbmM1eXJLYTd1eURWUEVVQkZsME1rWjFxNUlMZmlxNnQzRVBBQVNFOFJ6RU9UdEdhNHk4L29ORC96V1RPQT09', 'confirmed'),
(5, 2, '2025-12-27', '2025-12-30', 2, '+61400333444', 660.00, '141TVCoac9W0lj2dqioVnExORmlXNGYwcEVKSVVTRjFoN29jNW5sdzhaSzYvQWcweGVjWk43QjliOFMzaXloUTgrZy9PNVdWSjRMU0FKVzUwY0tZUkl2QkNiSEZuNnVQYUdBYmpwcm9NbmM1eXJLYTd1eURWUEVVQkZsME1rWjFxNUlMZmlxNnQzRVBBQVNFOFJ6RU9UdEdhNHk4L29ORC96V1RPQT09', 'confirmed'),
(4, 7, '2026-01-05', '2026-01-08', 3, '+61400111222', 585.00, '141TVCoac9W0lj2dqioVnExORmlXNGYwcEVKSVVTRjFoN29jNW5sdzhaSzYvQWcweGVjWk43QjliOFMzaXloUTgrZy9PNVdWSjRMU0FKVzUwY0tZUkl2QkNiSEZuNnVQYUdBYmpwcm9NbmM1eXJLYTd1eURWUEVVQkZsME1rWjFxNUlMZmlxNnQzRVBBQVNFOFJ6RU9UdEdhNHk4L29ORC96V1RPQT09', 'confirmed'),
(5, 8, '2026-01-12', '2026-01-15', 2, '+61400333444', 720.00, '141TVCoac9W0lj2dqioVnExORmlXNGYwcEVKSVVTRjFoN29jNW5sdzhaSzYvQWcweGVjWk43QjliOFMzaXloUTgrZy9PNVdWSjRMU0FKVzUwY0tZUkl2QkNiSEZuNnVQYUdBYmpwcm9NbmM1eXJLYTd1eURWUEVVQkZsME1rWjFxNUlMZmlxNnQzRVBBQVNFOFJ6RU9UdEdhNHk4L29ORC96V1RPQT09', 'confirmed');

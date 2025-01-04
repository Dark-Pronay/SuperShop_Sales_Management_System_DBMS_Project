CREATE DATABASE supershop;

USE SuperStore;

-- Create tables
CREATE TABLE Admin (
    A_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    A_pass VARCHAR(255) NOT NULL,
    A_name VARCHAR(255) NOT NULL
);

CREATE TABLE Store (
    S_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    S_pass VARCHAR(255) NOT NULL,
    S_city VARCHAR(255) NOT NULL,
    S_division VARCHAR(255) NOT NULL,
    S_A_id INT NOT NULL,
    FOREIGN KEY (S_A_id) REFERENCES Admin(A_id)
);

CREATE TABLE Distributor (
    D_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    D_name VARCHAR(255) NOT NULL,
    D_pass VARCHAR(255) NOT NULL,
    D_city VARCHAR(255) NOT NULL,
    D_type VARCHAR(255) NOT NULL,
    D_A_id INT NOT NULL,
    FOREIGN KEY (D_A_id) REFERENCES Admin(A_id)
);

CREATE TABLE Sales (
    Sales_id INT PRIMARY KEY NOT NULL,
    Sales_date DATE NOT NULL,
    Sales_revenue DECIMAL(10, 2) NOT NULL,
    Sales_S_id INT NOT NULL,
    FOREIGN KEY (Sales_S_id) REFERENCES Store(S_id)
);

CREATE TABLE Stock (
    Category VARCHAR(255) NOT NULL,
    Subcategory VARCHAR(255) NOT NULL,
    Quantity INT NOT NULL,
    St_S_id INT NOT NULL,
    PRIMARY KEY (Category, Subcategory, St_S_id),
    FOREIGN KEY (St_S_id) REFERENCES Store(S_id)
);

CREATE TABLE Store_order (
    O_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    O_date DATE NOT NULL,
    Shipment_stat ENUM('Delivered', 'Pending') NOT NULL,
    Payment_stat ENUM('Paid', 'Not paid') NOT NULL,
    So_S_id INT NOT NULL,
    So_D_id INT NOT NULL,
    FOREIGN KEY (So_S_id) REFERENCES Store(S_id),
    FOREIGN KEY (So_D_id) REFERENCES Distributor(D_id)
);

-- Set AUTO_INCREMENT values for each table
ALTER TABLE Store AUTO_INCREMENT = 101;
ALTER TABLE Distributor AUTO_INCREMENT = 201;
ALTER TABLE Sales AUTO_INCREMENT = 4001;
ALTER TABLE Store_order AUTO_INCREMENT = 3001;

-- Insert record into Admin table
INSERT INTO Admin (A_id, A_pass, A_name) 
VALUES (2104000, 'admin1', 'admin1');

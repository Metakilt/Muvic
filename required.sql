to make the Movies table:
CREATE TABLE Movies (
    movieId INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    releaseYear INT,
    description TEXT,
    imageUrl VARCHAR(255),
    rating DECIMAL(3, 1) DEFAULT 0.0
);

INSERT INTO Movies (title, releaseYear, description, imageUrl, rating, numRatings, category)
VALUES ('The Shawshank Redemption', 1994, 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', 'https://c8.alamy.com/comp/E1GJAR/the-shawshank-redemption-a-1994-american-drama-film-starring-tim-robbins-E1GJAR.jpg', 9.3, 0, 'Drama');
VALUES ('Forrest Gump', 1994, 'Forrest Gump is a 1994 American epic romantic-comedy-drama film based on the 1986 novel of the same name by Winston Groom.', 'https://upload.wikimedia.org/wikipedia/en/thumb/6/67/Forrest_Gump_poster.jpg/220px-Forrest_Gump_poster.jpg', 8.8, 0);



categories TABLE

CREATE TABLE Categories (
    categoryId INT AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(100) NOT NULL
);
INSERT INTO Categories (categoryName) VALUES ('Action'), ('Adventure'), ('Comedy'), ('Drama');


CREATE TABLE MovieCategories (
    movieId INT,
    categoryId INT,
    PRIMARY KEY (movieId, categoryId),
    FOREIGN KEY (movieId) REFERENCES Movies(movieId),
    FOREIGN KEY (categoryId) REFERENCES Categories(categoryId)
);
Shawshank immediately gets 1 as it autoincrements to just keep doing that for the rest. like 2 or 3.

INSERT INTO MovieCategories (movieId, categoryId)
VALUES (1, 4);
VALUES (2, 3);
INSERT INTO MovieCategories (movieId, categoryId)
VALUES (2, (SELECT categoryId FROM Categories WHERE name = 'Drama'));
CREATE TABLE Directors (
    directorId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    bio TEXT,
    imageUrl VARCHAR(255)
);
INSERT INTO Directors (firstName, lastName, bio, imageUrl)
VALUES ('Frank', 'Darabont', 'Frank Darabont is a Hungarian-American film director, screenwriter, and producer who has been nominated for three Academy Awards.', 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRSXWNNiktiCIBk9-NYJqAr9_F6AQSDkrK-e_bjEwMXd4MZEk_e');
VALUES ('Robert', 'Zemeckis', 'Robert Zemeckis is an American film director, producer, and screenwriter.', 'https://encrypted-tbn0.gstatic.com/licensed-image?q=tbn:ANd9GcT5u8sjV1AbBhjSgq2QV0XmosLZfjdcxpebi41ZTZjnHcBu5NM-mKO_eN-KtmMfrSt54Yp5gUQXDzdbHb4');

CREATE TABLE Direction (
    movieId INT,
    directorId INT,
    PRIMARY KEY (movieId, directorId),
    FOREIGN KEY (movieId) REFERENCES Movies(movieId),
    FOREIGN KEY (directorId) REFERENCES Directors(directorId)
);
INSERT INTO Direction (movieId, directorId)
VALUES (1, 1);
VALUES (2,2);

CREATE TABLE Admins (
    adminId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE users {
    id INT auto_increment PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
}
ALTER TABLE Users
ADD COLUMN isAdmin BOOLEAN DEFAULT FALSE;

CREATE TABLE Reviews (
    reviewId INT AUTO_INCREMENT PRIMARY KEY,
    movieId INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review TEXT NOT NULL,
    reviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movieId) REFERENCES Movies(movieId)
);
ALTER TABLE Reviews
ADD COLUMN userId INT,
ADD FOREIGN KEY (userId) REFERENCES Users(id);

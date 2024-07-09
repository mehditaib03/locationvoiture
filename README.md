# Car Rental Service API

This is a Symfony 6 project for a car rental service. 
It uses the FOSRestBundle for RESTful API creation, LexikJWTAuthenticationBundle for generating JWT tokens for authentication, and Symfony's test-pack for unit testing.

## Features

- RESTful API endpoints for managing car rentals.
- JWT authentication for securing the API.
- Unit testing with Symfony's test-pack.

## Requirements

- PHP 8.0 or higher
- Composer

### API Endpoints

### Generation du token 
lien : /api/login_check

**Request Body Json :**
{
	"username" : "taibmehdi15@gmail.com",
	"password" : "admin"
}

#### Affichage des Réservations d'un Utilisateur
**Endpoint: method{{GET}} **
 -  /api/reservations/{id}

**Response:**
[
	{
		"id": 33,
		"user": {
			"id": 1,
			"fullname": "mehdi",
			"email": "taibmehdi15@gmail.com"
		},
		"car": {
			"id": 2,
			"name": "AUDI",
			"model": "A6",
			"year": "2015"
		},
		"startTime": "2023-04-01T13:20:00+00:00",
		"endTime": "2023-04-01T15:00:20+00:00"
	}
]

#### Création de Réservation
**Endpoint:  method{{POST}} **
 -  /api/reservations
**Request Body Json :**

{
  "carId": 2,
  "startTime": "2023-04-01T10:00:00",
  "endTime": "2023-04-01T11:00:00"
} 
**Response:**
{
    "id": 32,
    "user": {
        "id": 1,
        "fullname": "mehdi",
        "email": "taibmehdi15@gmail.com"
    },
    "car": {
        "id": 2,
        "name": "AUDI",
        "model": "A6",
        "year": "2015"
    },
    "startTime": "2023-04-01T10:00:00+00:00",
    "endTime": "2023-04-01T11:00:00+00:00"
}

#### Modification de Réservation (PUT
**Endpoint:  method{{PUT}} **
 -  /api/reservations/{id}
**Request Body Json :**
{
  "carId": 2,
  "startTime": "2023-04-01T13:20:00",
  "endTime":   "2023-04-01T15:00:20"
}
**Response:**
"Reservation updated successfully"

#### Annulation de Réservation
**Endpoint: method{{DELETE}} **
 -  /api/reservations/{id}

**Response:**
"Reservation cancelled successfully"

#### Liste des Voitures  ######

**Endpoint: method{{GET}} **
 -  /api/cars

**Response:**
[
	{
		"id": 1,
		"name": "BMW",
		"model": "X5",
		"year": "2022"
	},
	{
		"id": 2,
		"name": "AUDI",
		"model": "A6",
		"year": "2015"
	}
]

#### Détails d'une Voiture  ######
**Endpoint: method{{GET}} **
 -  /api/cars/{id}
   
**Response:**
{
	"name": "AUDI",
	"model": "A6",
	"year": "2015"
}

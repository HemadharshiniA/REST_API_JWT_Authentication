# REST API WITH JWT AUTHENTICATION

This project is a secure REST API built using Core PHP and MySQL with JWT-based Access Token and Refresh Token authentication.

The API provides secure user authentication, protected routes, refresh token handling using HttpOnly Cookies, and CRUD operations for patient management. API testing is performed using Postman.

---

## Features

- MVC Architecture
- Access Token Authentication (JWT)
- Refresh Token Authentication
- HttpOnly Cookie Security
- Sliding Session Expiry
- Middleware Protection
- Password Hashing
- Environment Variables (.env)
- REST API Routing
- CRUD Operations
- User-wise Data Protection

---

## Authentication Flow

### Access Token
- Generated using JWT
- Short-lived token
- Expiry time: 1 minute
- Used to access protected APIs

### Refresh Token
- Random secure token
- Stored in:
  - HttpOnly Cookie
  - MySQL Database
- Long-lived token
- Expiry time: 7 days
- Used to generate new Access Tokens

### Token Refresh Flow

1. User logs in
2. Backend generates:
   - Access Token
   - Refresh Token
3. Access Token is returned in API response
4. Refresh Token is stored in HttpOnly Cookie and Database
5. When Access Token expires:
   - Client calls Refresh API
   - Backend validates Refresh Token from Database
   - New Access Token is generated
6. Session expiry is extended using Sliding Expiry mechanism

---

## Technologies Used

- PHP
- MySQL
- Apache
- JWT (HS256)
- Postman

---

## API Endpoints

### Authentication

POST /api/register
POST /api/login

### Patients

GET /api/patients
POST /api/patients
PUT /api/patients/{id}
DELETE /api/patients/{id}

## Security Features

- JWT Token Validation
- Password Hashing
- Protected Routes
- Prepared Statements

## Author

Hemadharshini A

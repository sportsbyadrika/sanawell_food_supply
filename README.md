# Dew Route Product Delivery System

Modern SaaS starter built with:

- Frontend: Next.js App Router + TypeScript + Tailwind CSS
- Backend: NestJS + Prisma + MySQL

## Project Structure

- `backend/nest-app` - API, JWT auth, role guards, Prisma schema
- `frontend/my-app` - Role-based dashboard UI and Axios API integration

## Setup

### 1) Backend

```bash
cd backend/nest-app
npm install
cp .env.example .env
npm run prisma:generate
npm run start:dev
```

### 2) Frontend

```bash
cd frontend/my-app
npm install
cp .env.example .env.local
npm run dev
```

## Roles

- `super_admin`
- `agency_admin`
- `office_staff`
- `driver`

## Security

- bcrypt password hashing
- JWT authentication
- Backend role guards
- Frontend protected dashboard route

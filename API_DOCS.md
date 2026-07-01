# NeedLink Minimal API Documentation

While the application primarily uses web routes with session-based authentication, the following routes function similarly to a RESTful API for handling core business logic.

## 1. Authentication
- `POST /auth/login`
  - **Description**: Authenticate a user and create a session.
- `POST /auth/register`
  - **Description**: Register a new user account.
- `POST /auth/logout`
  - **Description**: Terminate the current user session.

## 2. Service Requests
- `GET /dashboard/requests`
  - **Description**: Get list of service requests.
- `POST /dashboard/requests`
  - **Description**: Create a new service request.
- `PUT /dashboard/requests/{serviceRequest}`
  - **Description**: Update an existing service request.
- `DELETE /dashboard/requests/{serviceRequest}`
  - **Description**: Delete a service request.

## 3. Categories (Admin)
- `GET /dashboard/categories`
  - **Description**: List all categories.
- `POST /dashboard/categories`
  - **Description**: Create a new category.
- `PUT /dashboard/categories/{category}`
  - **Description**: Update a category.
- `DELETE /dashboard/categories/{category}`
  - **Description**: Delete a category.

## 4. Offers
- `POST /dashboard/offers`
  - **Description**: Submit an offer for a service request.
- `PUT /dashboard/offers/{offer}`
  - **Description**: Update a submitted offer.
- `DELETE /dashboard/offers/{offer}`
  - **Description**: Retract/delete an offer.
- `POST /dashboard/service-requests/{serviceRequest}/offers/{offer}/accept`
  - **Description**: Client accepts a specific offer (creates an order).
- `POST /dashboard/service-requests/{serviceRequest}/offers/{offer}/reject`
  - **Description**: Client rejects a specific offer.

## 5. Orders (Flow)
- `POST /dashboard/orders/{order}/delivery`
  - **Description**: (Provider) Submits work/delivery with attachments.
- `POST /dashboard/orders/{order}/request-revision`
  - **Description**: (Client) Requests revision on a submitted delivery.
- `POST /dashboard/orders/{order}/request-completion`
  - **Description**: (Either Party) Requests to mark the order as completed.
- `POST /dashboard/orders/{order}/request-completion/{completionRequest}/respond`
  - **Description**: (Other Party) Responds to the completion request (accepts and completes the order, or rejects and continues).

## 6. Order Interventions
- `POST /dashboard/orders/{order}/cancellation`
  - **Description**: Either party can request to cancel the order.
- `POST /dashboard/orders/{order}/cancellation/{cancellationRequest}/respond`
  - **Description**: Respond (accept/reject) to a cancellation request from the other party.
- `POST /dashboard/orders/{order}/dispute`
  - **Description**: Open a dispute to escalate to platform admins.
- `POST /dashboard/orders/{order}/dispute/respond`
  - **Description**: Provide a counter-reason/defense to an open dispute.
- `POST /dashboard/disputes/{dispute}/resolve`
  - **Description**: (Admin) Resolve an open dispute.
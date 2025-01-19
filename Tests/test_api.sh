#!/bin/bash

# Base URL of the API
BASE_URL="http://localhost:8000/api"

# Output file for API responses
OUTPUT_FILE="api_responses.txt"

# Function to print a separator
print_separator() {
    echo "--------------------------------------------------" | tee -a "$OUTPUT_FILE"
}

# Clear the output file
> "$OUTPUT_FILE"

# Generate a dynamic email address using the current date and time
EMAIL="test$(date +"%Y%m%d%H%M%S")@test.com"
echo "Using email: $EMAIL" | tee -a "$OUTPUT_FILE"

# Test registration
echo "Testing registration..." | tee -a "$OUTPUT_FILE"
print_separator
REGISTRATION_RESPONSE=$(curl -v -X POST "$BASE_URL/auth/register" \
    -H "Content-Type: application/json" \
    -d "{\"email\": \"$EMAIL\", \"password\": \"password123\", \"confirmPassword\": \"password123\"}" 2>&1)
echo "Registration Response:" | tee -a "$OUTPUT_FILE"
echo "$REGISTRATION_RESPONSE" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Check if registration was successful
if echo "$REGISTRATION_RESPONSE" | grep -q "User registered successfully"; then
    echo "Registration successful. Proceeding with login..." | tee -a "$OUTPUT_FILE"
else
    echo "Registration failed. Exiting..." | tee -a "$OUTPUT_FILE"
    exit 1
fi

# Test login and capture the token
echo "Testing login..." | tee -a "$OUTPUT_FILE"
print_separator
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/auth/login" \
    -H "Content-Type: application/json" \
    -d "{\"email\": \"$EMAIL\", \"password\": \"password123\"}")
echo "Login Response:" | tee -a "$OUTPUT_FILE"
echo "$LOGIN_RESPONSE" | tee -a "$OUTPUT_FILE"

# Extract token using grep and sed (alternative to jq)
TOKEN=$(echo "$LOGIN_RESPONSE" | grep -oP '"access_token":"\K[^"]+')
echo "Extracted Token: $TOKEN" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Test protected routes using the captured token
if [ -z "$TOKEN" ]; then
    echo "Failed to get token. Exiting..." | tee -a "$OUTPUT_FILE"
    exit 1
fi

# Fetch profiles for the authenticated user
echo "Fetching profiles for the authenticated user..." | tee -a "$OUTPUT_FILE"
print_separator
PROFILES_RESPONSE=$(curl -s -X GET "$BASE_URL/profile" \
    -H "Authorization: Bearer $TOKEN")
echo "Profiles Response:" | tee -a "$OUTPUT_FILE"
echo "$PROFILES_RESPONSE" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Create a profile for the user
echo "Creating a profile for the user..." | tee -a "$OUTPUT_FILE"
print_separator
PROFILE_RESPONSE=$(curl -s -X POST "$BASE_URL/profile" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{"name": "Test Profile", "media_preference": "MOVIE", "language_id": 1}')
echo "Profile Creation Response:" | tee -a "$OUTPUT_FILE"
echo "$PROFILE_RESPONSE" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Fetch profiles again to verify the new profile was created
echo "Fetching profiles for the authenticated user again..." | tee -a "$OUTPUT_FILE"
print_separator
PROFILES_RESPONSE=$(curl -s -X GET "$BASE_URL/profile" \
    -H "Authorization: Bearer $TOKEN")
echo "Profiles Response:" | tee -a "$OUTPUT_FILE"
echo "$PROFILES_RESPONSE" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Test getting all content
echo "Testing getting all content..." | tee -a "$OUTPUT_FILE"
print_separator
curl -X GET "$BASE_URL/content" \
    -H "Authorization: Bearer $TOKEN" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Test getting specific content
echo "Testing getting specific content..." | tee -a "$OUTPUT_FILE"
print_separator
curl -X GET "$BASE_URL/content/1" \
    -H "Authorization: Bearer $TOKEN" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Test getting all subscriptions
echo "Testing getting all subscriptions..." | tee -a "$OUTPUT_FILE"
print_separator
curl -X GET "$BASE_URL/subscription" \
    -H "Authorization: Bearer $TOKEN" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

# Test logout
echo "Testing logout..." | tee -a "$OUTPUT_FILE"
print_separator
curl -X POST "$BASE_URL/auth/logout" \
    -H "Authorization: Bearer $TOKEN" | tee -a "$OUTPUT_FILE"
echo -e "\n" | tee -a "$OUTPUT_FILE"

echo "API responses have been saved to $OUTPUT_FILE"
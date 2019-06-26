[![CircleCI](https://circleci.com/gh/michael-basweti/Job_Board.svg?style=svg)](https://circleci.com/gh/michael-basweti/Job_Board)
[![Build Status](https://travis-ci.com/michael-basweti/Job_Board.svg?branch=master)](https://travis-ci.com/michael-basweti/Job_Board)
[![Maintainability](https://api.codeclimate.com/v1/badges/68c8e1de448f51cca8ba/maintainability)](https://codeclimate.com/github/michael-basweti/Job_Board/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/68c8e1de448f51cca8ba/test_coverage)](https://codeclimate.com/github/michael-basweti/Job_Board/test_coverage)

# Job_Board Api
* This is a simple Api that allowes users applicants to apply for jobs and employers to post jobs
## Getting the swagger Documented Api
* visit the online API [HERE](https://whispering-crag-95331.herokuapp.com/)
* The documentation won't load automatically due to a bug in the package I'm using so do as the image below suggests
![Screenshot 2019-06-26 at 18 13 18](https://user-images.githubusercontent.com/23398223/60192864-505acb80-983f-11e9-9c77-42ff719d3163.jpg)

* On clicking Load Unsafe Scripts to load http over https, the following page will be loaded
<img width="1427" alt="Screenshot 2019-06-26 at 18 27 07" src="https://user-images.githubusercontent.com/23398223/60193308-19d18080-9840-11e9-808d-4081b7ba99e8.png">


## URLs
* The following are the urls one may use to interact with the API
### USER URLs
* register
#### POST::https://whispering-crag-95331.herokuapp.com/api/v1/users
```
{
    "name":"Michael",
    "email":"mike@gmail.com",
    "password":"hello_password",
    "role":"applicant"
}
role can either be applicant or employer
```
* login
#### POST::https://whispering-crag-95331.herokuapp.com/api/v1/login
```
{
    "email":"mike@gmail.com",
    "password":"hello_password"
}
```
* get all users
#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/users
* here you should pass Authorization token got from login. Should be in the format:
```
bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXN0ZXJpb3VzLWxha2UtNjc2ODEuaGVyb2t1YXBwLmNvbVwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE1NjA0NTI3NjAsImV4cCI6MTU2MDQ1NjM2MCwibmJmIjoxNTYwNDUyNzYwLCJqdGkiOiJXMkNrY0dMSWRzNUxMQm45Iiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.R2KvA1MSS3WaaWD_ZBbtFpCghKF_C4bqQbQNdcxg5yA
```
* get one user
#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/users/{id}
Pass id of the user you want to see, dont forget to pass authorization token

* edit user
#### PUT::https://whispering-crag-95331.herokuapp.com/api/v1/users/{id}
* Pass authorization token and id together with the body you want to update e.g
```
{
    "name":"Michael Basweti",
    "email":"mike@gmail.com",
}
```
* delete user
#### DELETE::https://whispering-crag-95331.herokuapp.com/api/v1/users/{}
* Pass id of the user you want to delete, dont forget to pass authorization token

<!-- ### Book URLs
* For the all the following URLs, You need to pass the bearer token in the headers
#### POST::https://whispering-crag-95331.herokuapp.com/api/v1/books
```
"title":"A man of the people",
"publisher":"Longhorn Publishers",
"year_of_publication":"2007",
"description":"A book on corrupt African leaders",
"author_id":1
```
* author_id should belong to an existing user
#### PUT::https://whispering-crag-95331.herokuapp.com/api/v1/books{id}
```
"title":"An enemy of the people",
"publisher":"Longhorn",
```
#### DELETE::https://whispering-crag-95331.herokuapp.com/api/v1/books{id}
* Here you just need to pass the id

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/books{id}
* Here you just need to pass the id to get a single book

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/books
* Get all books

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/books?sort_desc
* This will sort the books in a descending order

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/books?limit=2&offset=3
* This will paginates the books into what you want using limit and offset

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/books?author=mike
* This will return all the books written by mike

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/books?search=I have no idea
* This searches for a book by the title "I have no idea"

## Author URLs
* For the all the following URLs, You need to pass the bearer token in the headers
#### POST::https://whispering-crag-95331.herokuapp.com/api/v1/authors
```
"name":"Elijah Ominde",
"email":"elija@gmail.com",
"dob":"2007",
```

#### PUT::https://whispering-crag-95331.herokuapp.com/api/v1/authors{id}
```
"name":"Elijah Basweti",

```
#### DELETE::https://whispering-crag-95331.herokuapp.com/api/v1/authors{id}
* Here you just need to pass the id to delete an author

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/authors{id}
* Here you just need to pass the id to get a single authors

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/authors{id}/books
* Return all the books written by an author

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/authors
* Get all authors

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/authors?offset=1&limit=2
* Sets the limit and offset of the authors you want to get

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/authors?name=mike
* Searches for an author by the name Mike
 -->

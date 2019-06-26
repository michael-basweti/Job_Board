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

### Job URLs
* For the all the following URLs, You need to pass the bearer token in the headers
#### POST::https://whispering-crag-95331.herokuapp.com/api/v1/jobs
```
"title":"Job 1",
"description":"A nice Job",
"start_date":"01/02/2007",
"delivery_date":"01/01/2007",
"expected_income":"2000",
```
* author_id should belong to an existing user
#### PUT::https://whispering-crag-95331.herokuapp.com/api/v1/jobs{id}
```
"title":"Job 1",
"description":"A nice job",
```
#### DELETE::https://whispering-crag-95331.herokuapp.com/api/v1/jobs{id}
* Here you just need to pass the id

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs{id}
* Here you just need to pass the id to get a single book

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs
* Get all jobs

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs?sort_desc
* This will sort the jobs in a descending order

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs?sort_asc
* This will sort the jobs in ascending order

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs?limit=2&offset=3
* This will paginates the jobs into what you want using limit and offset

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs?search=I+have+no+idea
* This searches for a job by the title "I have no idea"

## Applications URLs
* For the all the following URLs, You need to pass the bearer token in the headers
#### POST::https://whispering-crag-95331.herokuapp.com/api/v1/applications
```
"title":"Application 1",
"description":"hello job",
"job_id":1
```

#### PUT::https://whispering-crag-95331.herokuapp.com/api/v1/applications/{id}
```
"description":"hello job"

```
#### DELETE::https://whispering-crag-95331.herokuapp.com/api/v1/applications/{id}
* Here you just need to pass the id to delete an application

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/applications/{id}
* Here you just need to pass the id to get a single application

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/jobs/{id}/applications
* Return all the applications for a given job

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/applications
* Get all applications

#### GET::https://whispering-crag-95331.herokuapp.com/api/v1/applications?offset=1&limit=2
* Sets the limit and offset of the applications you want to get



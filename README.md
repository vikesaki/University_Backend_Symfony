# Symfony API
to build the project, use docker command <br />
```
docker build -t symfony_project .
```
<br />
and run the docker with 8000 as the port with 
<br />

```
docker run -d -p 8000:8000 --name my_symfony_app symfony_project
```

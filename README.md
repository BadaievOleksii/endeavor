# Endeavor

**PHP library, abstracting the messaging workflow with RabbitMQ message broker**

# Usage
Should be required as a Composer dependency in separate project, and 
then it can be used via either bundled binary script, or as a 
library in other application's code

### Direct binary usage
First option is to use Endeavor binary directly:   
```
php vendor/bin/endeavor -d config/endeavor/ pipeline:send #PIPELINE-ID#

php vendor/bin/endeavor -d config/endeavor/ pipeline:receive #PIPELINE-ID# #STAGE-ID#
```
Any command has parameter '-d --dir' that should point to folder containing `services.yml` file
It should be a valid Symfony Service Container definition, where 
`#PIPELINE-ID#` will be service id for instance of `Endeavor\Tasks\TaskPipeline` class   
**See full example in `config/services.yml`** 


### Library code usage
Endeavor can also be used as a set of components, that encapsulates
low-level queueing logic from client code, providing simple abstraction
of `Task` and `TaskProcessor`, `Consumer` and `Producer`   
**Detailed description: https://www.enterpriseintegrationpatterns.com/patterns/messaging/ControlBus.html**
   
   
## Docker image is included for running unit tests
```
docker build docker
```

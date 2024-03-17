# How the project is structured

The project is structured as follows:

```
.
├── .castor (files for the Castor tool)
├── .docker (docker related files)
├── .github (github actions, workflows, etc)
├── app/ (the main application)
│   ├── modules (the modules of the application, like API management, etc)
│   ├── src/ (the source code of the application)
│   │   └── User/ (Scope of the domain)
│   │       ├── Controller/
│   │       │   ├── GetOneUserByIdController.php
│   │       │   ├── GetUserCollectionController.php
│   │       │   ├── CreateUserController.php
│   │       │   └── DeleteUserController.php
│   │       ├── Exception/
│   │       │   ├── AbstractUserException
│   │       │   └── UserNotFoundException.php
│   │       ├── Enum/ 
│   │       │   └── UserRoleEnum.php
│   │       ├── ValueObject/ (the value objects that will be used by the User domain in for output, input in service/manager layer)
│   │       │   ├── UserCollection.php
│   │       │   └── User.php
│   │       ├── Entity/ (the entities that will be used by the User domain for only persistence)
│   │       │   └── UserEntity.php
│   │       ├── Serialization/ (the serialization classes that will be used by the User domain)
│   │       │   └── UserGroups.php
│   │       └── Configuration/ (if the module needs to be configured, the configuration classes will be here)
│   │           └── UserAutoMapperConfiguration.php
│   └── config
├── tools/ (static analysis, code quality, etc)
│   ├── phpstan
│   ├── ecs
│   ├── rector
│   └── ...
├── docs (the documentation of the project, powered by mkdocs)
├── castor.php 
├── composer.yaml
└── mkdocs.yml
# Contribution Guide

First setup the project by following the Readme instructions [here](./README.md)

Create a new branch from your actual one like this :
```
 git checkout -b feature/DEV-XXX
```

Make your modifications then commit and push your branch
```
 git push -u origin feature/DEV-XXX
```

Create a pull request and let the automated tests run, wait for reviews if need be

Finally, merge your branch into the main one then delete yours

## Conventions

The main production branch is Master, changes should only be directly merged to it in case of hotfixes.
In most other cases, changes should be merged into the current release branch.

New develoments follow a 2 week sprint schedule, with a release branch named release-x.x.x
(the x's being the current version), after the 2 weeks have passed, if hotfixes were made to Master, we do a Master to Release merge of those modifications, the release branch is merged into master,
a tag is created as well as a new release branch that is checked out from the old one.

branches should follow the following naming conventions:
 - feature/DEV-XXX for a new feature
 - fix/DEV-XXX for a fix
 - hotfix-/DEV-XXX for a hotfix

Commits should be named:
```
 DEV-XXX: commit description
 ```

Automatic tests are run at commit time

When a new pull request is opened on GitHub or a new push is done, automatic tests need to finish running on GitHub
Actions before being able to merge, this is done to prevent code regressions.
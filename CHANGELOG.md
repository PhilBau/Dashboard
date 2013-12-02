DASHBOARD
=========

## 0.9.1
--------

- Closed feature request #3: configuration for widgets
Module dev are now able to provide a form and a template in order to add customization parameters to their widgets. 
- Closed discussion #5: New functionalities in Dashboard
- Added a new level of widgets: 'default widgets'.
They are added by default to every registered users. The change of the parameters of the default widgets require a minimum level of permission. Members of the administrators group are the only ones allowed to add and remove default widgets.
- Added specific look and feel for the default widgets via the css stylesheet. 
- Show/hide default widgets for specific groups of users using Zikula permissions.
- Overhaul of level permissions: ACCESS_OVERVIEW (default widgets read only users), ACCESS_READ (same as in the 0.9.0 version, possibility to add/edit/remove own widgets), ACCESS_MODERATE (add possibility to edit default widgets), ACCESS_ADMIN (add possibility to add/remove default widgets).
- Add new component permission : Dasboard::defWidget | Widget Id:: | none/READ
Used to show or hide default widgets. 
- Upgrade routine from 0.9.0 module version checked.

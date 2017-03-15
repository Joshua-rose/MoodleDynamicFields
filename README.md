# MoodleDynamicFields
Dynamic Menu fields for user profiles


## DataBase Settings
### Field Types

Field Type| Field Number | Notes
----------|-------------|-------
Parent | 0 | Standard Menu type
Hide/Show | 1 | Will endable/disable in Version 1.0 +
Update|2| Will only show items that correspond with the parent values marked in the trigger values
Update/Show | 3| will enable and update in Version 1.0 +

### DataBase Cheat Sheet

Field Type                    | Param1               | Param2 | Param3       | Param4                          | Param5         | Notes
----------------------------- | -------------------- | ------ | ------------ | ------------------------------- | -------------- | --------------------------------------------------
Parent with Hide/Show Child   | Field Values         | 0      | 1            | Name of Child to Show           |                | The fields in Param4 Must correspond to the Param1
Parent with Update Child      | Field Values         | 0      | 2            | Name of Child to update         |                | The fields in Param4 Must correspond to the Param1
Parent with Update/Show Child | Field Values         | 0      | 3            | Name of Child to update or show |                | The fields in Param4 Must correspond to the Param1
Hide/Show Child               | Field Values         | 1      | [child type] | [Name of Child]                 |                |
Update Child                  | Default Field Values | 2      | [child type] | [Name of Child]                 | Trigger Values | Trigger values must match Parent values
Update/Show Child             | Default Field Values | 3      | [child type] | [Name of Child]                 | Trigger Values | Trigger values must match Parent values

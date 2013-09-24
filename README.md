Elements: Storage
=================

###### Element: Storage

Storage element is an [abstract concept independent of implementation](./Interface.php) (flat files, database tables, memcached,…). If an app requires a storage, it should have no idea about the implementation, but it should require storage to store [objects](./Blob/AwareInterface.php) or to [have indexes](./Blob/AwareInterface.php) to perform search.

> ***Heads-up:** Interface is a wrapper of the AwareInterface, the engine.*

Types of storage (beta):

- [Blob Storage](./Blob/Element.php) – can store any object
- [Index Storage](./Index/Element.php) – can store single object's attribute
  (more values are possible as for example many full-text indexes)
  
Types of storage (todo):

- [HasMany Storage](./#todo) – e.g. Followers of a User, Statuses of a User; These are the situations where Blob Storage could not handle the number of records in the serialised fields. Extra storage is inevitable. Interchangeability could/should be maintained.

Goal
----

**The goal is to create elegant, yet simple, but flexible persistent storage layer.**

The goal is not to replicate a NoSQL solution for Flat-file or to create onother database ORM or ActiveRecord. In fact, both RDBMS and NoSQL solution have their pros and cons. NoSQL has schema flexibility, RDBMS has great power to search through the records and great data integrity. The idea is to get them work well together, mix them like:

- store text document as files on the disk, easily accessible through some URL, and indexable to perform search upon them, which can be stored in the database;
- begin with no schema, fix some schema later, create indexes on the fly, split data to different table;
- fast start withou schema for testing some scenarios
- others I can't figure out away right now ^.^

State
-----

Stable but likely to change as I am for example not sure if there is a need to have three different Interfaces (Index_Interface, Storage_IndexableInterface, Index_AwareInterface) and probably many other quirks.

Some methods still return nulls, which I try to change to throwing exceptions.

**Enjoy!**

[@martin_adamko](http://twitter.com/martin_adamko)  
*Say hi on Twitter*
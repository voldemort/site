<?php require "includes/header.inc" ?>

<h2>Fun Projects</h2>

<p>
This is a list of fun project ideas that no one is currently working on.
</p>

<h3>1. Views</h3>

<p>
Relational databases usually have the ability to create views on a table that transform the data in that table. The advantage of this is that it moves the computation closer to the data (i.e. the processing that creates the view is done in the same process in which the data is cached); the disadvantage is that if the database is a centralized system then moving more computation there is not necessarily desirable. In a distributed system like Voldemort, moving the computation for the data filtering or transformation to the server does not have the same centralization drawbacks because the servers themselves are scalable.
</p>

<p>
This project is to add views on top of the key-value blob model in Voldemort. An example use case would be to have a store that contains lists of items an be able to filter down the list by different criteria in different views without transferring all the data to the server.
</p>

<p>
A simple implementation would allow the ability to statically create views by providing a jar file specifying the data transformation, and would provide a view that appears as a virtual store in voldemort by transforming the data in an existing store.
</p>

<p>
A more powerful implementation would provide a dynamic interface for creating and removing views via providing a simple transformation function in JVM-based language such as Scala or javascript.
</p>

<h3>2. Publish/Subscribe API</h3>

<p>Storage systems have become much more specialized in recent years with each system providing expertise in certain areas--Hadoop and proprietary data warehouses provide batch processing capabilities, Search indexes provide support for complex ranked text queries, and a variety of distributed databases have sprung up. Voldemort is a specialized key-value system, but the same data stored in Voldemort may need to be indexed by search, churned over in hadoop, or otherwise processed by another system. Each of these systems needs the ability to subscribe to the changes happening in Voldemort and get a stream of such changes that they can process in their own specialized way.	
</p>

<p>Indeed even voldemort nodes could subscribe to one another as a quick catch-up mechanism for recovering from failure.</p>

<p>Amazon has implemented this functionality as a "Merkle tree" data structure in their Dynamo system which allows nodes to compare their contents quickly and catch up to differences they have missed, but this is not the only approach. It could be a simple secondary index that implements a node-specific logical counter that tracks modification number for each key.</p>

<p>The api that would be provided would be something like getAllChangesSince(int changeNumber), and this api would provide the latest change for each key.

<h3>3. New Clients</h3>

<p>
There is a protocol buffers network protocol for accessing the voldemort server. This goal of this project would be to create a python, c++, or other protocol buffers client to provided an excellent interface to the system that models the guarantees the system provides in the best possible way in the implementation language.
</p>

<p>
A minimal implementation must allow the client to provide the ability to deal with conflicting results and deal with server failure (by reconnecting to another node).
</p>

<p>
The network protocol is pluggable so a slightly more difficult implementation could add both a network protocol and a client (say in a language not well supported by protocol buffers).
</p>

<h3>4. Geographical Data Distribution</h3>

<p>
One thing key-value stores are good at is supporting data that is geographically distributed. The versioning mechanism in Voldemort is specifically designed to allow writes to occur in geographically disparate locations and be merged without data loss.
</p>

<p>
This project would involve adding a location key to the metadata stored about each node in the cluster, and implementing a new routing strategy that made intelligent distance-aware decisions about which nodes it chose to route to. The programming portion of this project is not that large, mostly just implementing a function that maps from keys to voldemort nodes and updating the configuration. However the algorithm design and verification portion are more substantial since the goal is to have a strategy that will efficiently distribute data with all routing decisions being made locally.
</p>

<p>
A full implementation would come with tests that add artificial timeouts and test correct routing decisions. An excellent implementation would also come with an Amazon EC2 test that ran in multiple EC2 availability regions (i.e. in actual geographically distributed data centers).
</p>

<h3>5. NIO based server</h3>

<p>
The socket server uses a single-thread per connection. This approach is very efficient, but becomes less efficient for scaling beyond hundreds of connections. An NIO socket server could be plugged in for better support for very large clusters. The deliverable would be a NIO-based connector that could be plugged in in place of the existing approach when appropriate.
</p>

<h3>6. Operational Interface</h3>

<p>
One of the primary problems for a practical distributed system is knowing the state of the system. Voldemort has a rudimentary GUI that provides basic information. This project would be to make a first rate management GUI and corresponding control functionality to be able to know the performance and availability of each node in the system as well as perform basic operations such as starting and stopping nodes (or the whole cluster), performing queries, etc.
</p>

<p>
Part of this project would be providing remote access to the administrative functionality that the GUI can invoke. Some of the basic administrative functionality could be shared with the Scala shell project.
</p>

<h3>7. Scala Voldemort Shell</h3>

<p>
Voldemort comes with a very simple text shell. A better way to build such a thing is to fully integrate a language with an interpreter and provide a set of predefined administrative commands as functions in the shell. Scala has a flexible syntax and integrates easily with Java so it would be a good choice for such a shell.
</p>

<p>
Part of this project would be providing the administrative commands that the shell could invoke. Some of the basic administrative functionality could be shared with the Operational Interface project.
</p>

<h3>8. Export Data to Hadoop</h3>

<p>
Voldemort is an online system for performing simple, low-latency queries at high volume. But a common need is to do data analytics across all the data in the system. This is something best done in a first-rate batch processing system like Hadoop. This project would be to build a MapReduce job that streams data into Hadoop (say by mapping over data partitions and having each mapper stream in the data from the given partition). Data reconciliation of data versions would need to be done so that the Hadoop cluster ends up with only the reconciled version of each key. A first rate implementation might give a second job for aiding data processing in the appropriate serialization format used by the Voldemort store.
</p>
<p>
Depending on the implementation strategy this could be integrated with the subscription mechanism project given above.
</p>
<p>
Since Voldemort is an online system it is important that the streaming data transfer does not impact the performance of the system too severely (it may need some throttling).
</p>

<?php require "includes/footer.inc" ?>
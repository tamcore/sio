### Done
* Actions
    * Incoming
        * DND-mode
        * \<Dial\> (```<Number></Number>, <Voicemail />```)
        * \<Play\> (```<Play><Url>http://example.com/example.wav</Url></Play>```)
        * \<Reject\> (```<Reject reason="rejected"></Reject>``` and ```<Reject reason="busy"></Reject>```)
    * Outgoing
        * number matching via regex
        * \<Dial\> (```<Dial anonymous="true"> and <Dial callerId="49123456789">```)

### ToDo
* call logging to database (enable/disable on a per-number base)
* notifications (email, telegram?, ..) on user-defined actions
* user registration (enable/disable via config.php, verify number via whatsapp -> cheapest way)
* responsive webinterface (mobile friendly!!)

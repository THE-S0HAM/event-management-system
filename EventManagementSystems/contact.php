<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laxmi Trimbak Lawns - Contact Us</title>
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
    </head>
    <body>
        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class = "content"><!--body content holder-->
            <div class = "container">
                <div class = "col-md-12"><!--body content title holder with 12 grid columns-->
                    <h1>Contact Us</h1><!--body content title-->
                </div>
            </div>
			
            <div class="container">
            <div class="col-md-12">
            <hr>
            </div>
            </div>
            
            <div class="container">
                <div class="col-md-6 contacts">
                    <h1><span class="glyphicon glyphicon-home"></span> Laxmi Trimbak Lawns</h1>
                    <p>
                        <span class="glyphicon glyphicon-map-marker"></span> Address: JM5G+VP5, Waladgaon, Shrirampur, Maharashtra 413709<br>
                        <span class="glyphicon glyphicon-envelope"></span> Email: laxmitribaklawns@gmail.com<br>
                        <span class="glyphicon glyphicon-phone"></span> Phone: Contact for details
                    </p>
                    
                    <h3>Our Venues</h3>
                    <p>
                        <strong>Mangal Karyalay</strong> - Our premium venue for large weddings (Capacity: 500)<br>
                        <strong>Vivah Hall</strong> - Perfect for engagement ceremonies and smaller gatherings (Capacity: 300)<br>
                        <strong>Lawn A</strong> - Spacious outdoor venue for grand celebrations (Capacity: 800)
                    </p>
                </div>
                <div class="col-md-6">
                    <form>
                        <div class="form-group">
                            <label for="Title">Name:</label>
                            <input type="text" name="name" id="Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Email">Email:</label>
                            <input type="email" name="email" id="Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Phone">Phone:</label>
                            <input type="text" name="phone" id="Phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="EventType">Event Type:</label>
                            <select name="eventType" id="EventType" class="form-control">
                                <option value="">Select Event Type</option>
                                <option value="Wedding">Wedding</option>
                                <option value="Reception">Reception</option>
                                <option value="Engagement">Engagement</option>
                                <option value="Sakharpuda">Sakharpuda</option>
                                <option value="Haldi">Haldi</option>
                                <option value="Sangeet">Sangeet</option>
                                <option value="Mehendi">Mehendi</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Message">Message:</label>
                            <textarea id="Message" name="message" rows="6" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">Send Inquiry <span class="glyphicon glyphicon-send"></span></button>
                    </form>
                </div>
            </div>
            
            <div class="container">
                <div class="col-md-12">
                    <hr>
                    <div class="map-responsive">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3767.5!2d74.6!3d19.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTnCsDM2JzAwLjAiTiA3NMKwMzYnMDAuMCJF!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div><!--body content div-->
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
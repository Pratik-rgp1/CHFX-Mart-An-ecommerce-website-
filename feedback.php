<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/feedbac.css" />
    <title>Feedback</title>
  </head>
  <body>
    
    <div class='nav-bar'>
    <?php
    include ('nav.php');
    ?>
  </div>

    <div class="container">
      
      <div class="feedback_img">
        <img src="images/feedback.png" />
      </div>

      <div class="form-container">
        <form action="">
          <h1>Give Your Feedback</h1>
          <label for="">Name</label>
          <input type="text" placeholder="Full Name." required />
          <label for="">Email</label>
          <input type="email" placeholder=" Your Email." required />
          <label for="number-input">Phone Number</label>
          <input
            type="text"
            placeholder="Phone number"
            id="number-input"
            pattern="\d*"
            maxlength="10"
          />
         
          <script>
            const numberInput = document.querySelector("#number-input");

            numberInput.addEventListener("input", function (event) {
              const input = event.target.value;
              event.target.value = input.replace(/[^0-9]/g, "");
            });
          </script>
          <label for="">Feedback Message</label>

          <input
            type="text"
            pattern="[a-z]"
            placeholder="Your message."
            required
          />
          

          <div class="submit">
            <input type="submit" value="Submit" />
          </div>
          <div class="txt_feedback">
            <h3>
              "Your feedback is our compass, and it guides us towards creating a
              product that meets your needs and exceeds your expectations."
            </h3>
          </div>
        </form>
      </div>
    </div>
  
  <div>
    <?php
    include ('footer.php');
    ?>
  </div>
  
  </body>
</html>
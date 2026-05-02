<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

include('../includes/db.php');
include('reminder.php'); 

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, last_period FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $last_period);
$stmt->fetch();
$stmt->close();

// // Fetch latest symptom for this user
// $latest_symptom_text = '';
// $latest_symptom_date = '';
// $symptoms_stmt = $conn->prepare("SELECT symptoms_text, symptom_date FROM user_symptoms WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
// $symptoms_stmt->bind_param("i", $user_id);
// $symptoms_stmt->execute();
// $symptoms_stmt->bind_result($latest_symptom_text, $latest_symptom_date);
// $symptoms_stmt->fetch();
// $symptoms_stmt->close();

// // Run diagnosis logic if there is a latest symptom
// $diagnosis_html = '';
// if(!empty($latest_symptom_text)){
//     $threshold = 1.0;
//     $input_text = strtolower(trim($latest_symptom_text));

//     // Fetch all symptoms
//     $symptoms = [];
//     $result = $conn->query("SELECT symptom_id, symptom_name FROM symptoms");
//     while ($row = $result->fetch_assoc()) {
//         $symptoms[$row['symptom_id']] = strtolower($row['symptom_name']);
//     }

//     function symptom_in_text($symptom, $text){
//         $pattern = '/\b' . preg_quote($symptom, '/') . '\b/';
//         return preg_match($pattern, $text);
//     }

//     $matched_symptom_ids = [];
//     foreach($symptoms as $id=>$name){
//         if(strlen($name) > 2 && symptom_in_text($name, $input_text)){
//             $matched_symptom_ids[] = $id;
//         }
//     }

//     if(count($matched_symptom_ids) > 0){
//         $ids_str = implode(',', $matched_symptom_ids);
//         $sql = "SELECT c.condition_name, c.severity, c.advice, SUM(sc.weight) as score
//                 FROM symptom_condition sc
//                 JOIN conditions c ON c.condition_id = sc.condition_id
//                 WHERE sc.symptom_id IN ($ids_str)
//                 GROUP BY c.condition_id
//                 HAVING score >= $threshold
//                 ORDER BY score DESC
//                 LIMIT 3";
//         $res = $conn->query($sql);

//         if($res && $res->num_rows > 0){
//             $diagnosis_html = "<h5>Possible conditions:</h5><ul>";
//             while($row = $res->fetch_assoc()){
//                 $diagnosis_html .= "<li><strong>".htmlspecialchars($row['condition_name'])."</strong><br>";
//                 $diagnosis_html .= "Severity: ".htmlspecialchars($row['severity'])."<br>";
//                 $diagnosis_html .= "Advice: ".htmlspecialchars($row['advice'])."<br>";
//                 $diagnosis_html .= "Score: ".round($row['score'],2)."</li><br>";
//             }
//             $diagnosis_html .= "</ul>";
//         } else {
//             $diagnosis_html = "No matching condition found with sufficient confidence.";
//         }
//     } else {
//         $diagnosis_html = "No symptoms matched from your input.";
//     }
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Home | PregPal</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" />
</head>
<body>

<?php include('../includes/header.php'); ?>

   
<div class="dashboard">
 
    <div class="calendar">
        <div id="calendar"></div>
    </div>

    <div class="week-info">
<!-- calculate pregnancy -->
        <div id="pregnancyWeek" style="margin-top: 15px; font-size:18px; font-weight:500;">
            <?php if($last_period): ?>
                <?php
                $today = new DateTime();
                $lp = new DateTime($last_period);
                $interval = $lp->diff($today);
                $weeks = floor($interval->days / 7);
                $days = $interval->days % 7;
                echo "You are currently $weeks Week & $days days";
                ?>
                
            <?php else: ?>
                <span style="color: #d44c2e; font-style: italic;">
                    Please enter your pregnancy date to calculate the weeks of pregnancy
                </span>
            <?php endif; ?>
        </div>

           <div id="diagnosisweek" class="mt-3 p-2 border rounded bg-light">
            
    <?php
    
//     // Fetch last diagnosis from DB
//   $diag_stmt = $conn->prepare("
//     SELECT diagnosis_html
//     FROM user_diagnosis
//     WHERE user_id = ?
//     ORDER BY created_at DESC, diagnosis_id DESC
//     LIMIT 1
// ");
// $diag_stmt->bind_param("i", $user_id);
// $diag_stmt->execute();
// $diag_stmt->bind_result($last_diag_html);
// $diag_stmt->fetch();
// $diag_stmt->close();


$week_text='';
if (empty($last_period)) {
    $week_image = '../assets/images/weeks/chart.png';
}
    //  Determine the image based on week   
    elseif ($weeks ==1 ) {
        $week_image = '../assets/images/weeks/chart.png';
      
    } elseif ($weeks ==2) {
        $week_image = '../assets/images/weeks/2.png';
         $week_text  = "You're not pregnant yet, but if you conceive this week, you'll be two weeks pregnant. 
                        That's because healthcare providers use your last menstrual period to determine your due date, 
                        so technically the first day of your period is also the first day of your pregnancy. ";
    } elseif ($weeks ==3) {
        $week_image = '../assets/images/weeks/3.png';
         $week_text  = "Your developing baby is a tiny ball of several hundred cells that are multiplying 
                        and burrowing into the lining of your uterus. The cells in the middle will become the embryo. 
                        The cells on the outside will become the placenta, 
                        the pancake-shaped organ that delivers oxygen and nutrients to your baby and carries away waste.

                        Most women don't feel anything until they've missed a period, but you may notice bloating, cramping, or spotting this week. 
                        Your breasts may also be more tender than usual and you may have a heightened sense of smell";
    } elseif ($weeks ==4) {
        $week_image = '../assets/images/weeks/4.png';
         $week_text  = "Your baby, or embryo, is about 2mm long (about the size of a poppy seed) and growing rapidly in your womb.
                        It's protected by an amniotic sac, which is filled with cushioning fluid, and attached to a tiny yolk sac 
                        that provides all the nourishment it needs.The outer layer will later develop into the placenta and provide 
                        your baby with oxygen and nutrients.
                        
                        You probably don't look pregnant yet. If it's your first pregnancy you might not start showing until at least week 12.
                        However, if this isn't your first baby, you may start showing sooner, as the muscles in your uterus (womb) and 
                        belly may have been stretched from your last pregnancy.";

    } elseif ($weeks ==5) {
        $week_image = '../assets/images/weeks/5.png';
         $week_text  = "Your baby, or embryo, is around 2mm long (about the size of a sesame seed).
                        The face is starting to take shape, with a tiny nose and little eyes, which stay closed until around 28 weeks. 
                        Your baby's brain and spinal cord are forming rapidly inside you.
                        
                        It's still early days, and many women will not know they're pregnant at 5 weeks.
                        Not everyone has regular menstrual cycles, so you may not realise that your period is late. 
                        You might notice some light bleeding, and think it's your period, but it can also be a sign of implantation bleeding ";
    }elseif ($weeks ==6) {
        $week_image = '../assets/images/weeks/6.png';
         $week_text  = "Your baby, or embryo, is around 6mm long, which is about the size and shape of a pea.
                        The arms and legs are starting to form and are known as limb buds. There are tiny dents where the ears will be.
                        There's a bump where the heart is and another bulge where the head will be.
                         
                        You may be dealing with morning sickness and tiredness, along with other early signs of pregnancy.
                         Morning sickness can occur at any time of day, although it's usually worse when you first wake up. It might help to keep a snack by your bed.";
    }elseif ($weeks ==7) {
        $week_image = '../assets/images/weeks/7.png';
         $week_text  = "Your baby, or embryo, is around 10mm long from head to bottom, which is about the size of a grape.
                        The brain is growing faster than the rest of the body, so they have a large forehead. 
                        There are small dimples where the nose and ears will be. The eyelids are beginning to grow and cover the eyes.
                        
                        As you go through your pregnancy, the volume will increase by up to 50%. The extra blood will feed your uterus (womb) with all the oxygen and nutrients that your baby needs.
                        This can make you feel thirstier than usual. Try to drink 8 medium glasses of fluid a day. ";
    }elseif ($weeks ==8) {
        $week_image = '../assets/images/weeks/8.png';
         $week_text  = "Your baby is now around 16mm long, which is about the size of a raspberry. By next week, they will be twice the size!
                        The tiny head has started to uncurl a bit. 
                        Their arms are getting longer and are bigger than the legs as the upper part of the body grows faster than the lower part. 
                        The legs are getting longer too, although the knees, ankles, thighs and toes have not developed yet.
                        
                        You may be feeling tired and sick, you could find yourself peeing more often as your expanding womb pushes onto your bladder. If this starts to affect your sleep, 
                        try to drink lots of fluids in the day but less in the evenings.";
    }elseif ($weeks ==9) {
        $week_image = '../assets/images/weeks/9.png';
         $week_text  = "Your baby, or foetus, is now around 22mm long from head to bottom, which is about the size of a strawberry.
         The face is looking more recognisable, with eyes protected by eyelids, a little mouth and even a tongue with tiny taste buds.
         The hands and feet are developing, but there are no fingers or toes yet, just grooves where they will be.
         All the major internal organs – the heart, brain, lung, kidneys and gut – are developing. Bones are starting to form.
         
         Your breasts will be bigger now and your waist may be thickening a little. 
         Your pregnancy hormones are flooding your body, which may still be causing you to feel unwell.";

    }elseif ($weeks ==10) {
        $week_image = '../assets/images/weeks/10.png';
         $week_text  = "Your baby, or foetus, is now around 30mm long from head to bottom, which is about the size of a small apricot.
The baby will be making jerky movements and baby's movement can be seen on a scan.
Your baby is going through another huge growth spurt. The head is still too big for the body, but the face is more recognisably in proportion.
The eyes are half closed but can react to light.

You may be feeling bloated and you might find yourself burping or passing wind – this is due to your hormones. 
The female hormone progesterone is just doing its job – relaxing the muscles in your womb so that it can expand along with your growing baby.
However, in the process, the muscles in your digestive tract also become looser and this can lead to symptoms such as heartburn.";
    }elseif ($weeks ==11) {
        $week_image = '../assets/images/weeks/11.png';
         $week_text  = "Your baby, or foetus, is now around 41mm long from head to bottom, which is about the size of a fig. 
         The head is still supersized, but the body is growing quickly.
Although your baby is kicking around inside your womb, you probably won't feel anything for several weeks.
         
         As you start to bulge out a bit, your muscles and ligaments will stretch, and this could give you pains around your stomach. 
         If it hurts a lot, see your midwife or doctor as soon as possible.";
    }elseif ($weeks ==12) {
        $week_image = '../assets/images/weeks/12.png';
         $week_text  = "Your baby, or foetus, is now 5.4cm long from head to bottom, which is about the size of a plum.
The internal organs and muscles have grown, and the heartbeat can be picked up on an ultrasound scan.
 The skeleton is made up from tissue and hardening into bone.
         
 Your waist is probably thickening and your breasts getting bigger. As the sickness subsides, you may start to feel hungrier and 
         worry if you're eating enough for you and the baby.";
    }elseif ($weeks ==13) {
        $week_image = '../assets/images/weeks/13.png';
         $week_text  = "Your baby, or foetus, is around 7.4cm long, which is about the size of a peach.
Your baby's ovaries or testes are fully developed inside and final tweaks are being made on the outside.
The baby is moving around. At first the movements are very jerky and random but then they start to look more deliberate.

         A small baby bump may now be visible as your uterus (womb) grows upwards and outwards. 
         If you've been feeling the urge to pee more often, that should stop as the womb moves away from your bladder.";
    }elseif ($weeks ==14) {
        $week_image = '../assets/images/weeks/14.png';
         $week_text  = "Your baby, or foetus, is around 8.5cm long from head to bottom, which is about the size of a kiwi fruit. The head is getting rounder 
         and more in proportion with the rest of the body.
         
         Hopefully you are starting to feel less tired and sick now. As your energy levels return, your appetite might too.
Remember, your baby does not need any extra calories at this point. Too much weight gain in pregnancy is not good for you or your baby.
If you get hungry between meals try sticking to healthy snacks.";
    }elseif ($weeks ==15) {
        $week_image = '../assets/images/weeks/15.png';
         $week_text  = " Your baby, or foetus, is around 10.1cm long from head to bottom, which is about the size of an apple.
Your baby has been busy growing a soft layer of hair all over their body. Their eyebrows and eyelashes are also starting to develop. Your baby's eyes are now sensitive to light.
Around now, your baby will start hearing too. Talk to your baby and they will probably hear you. They will also hear your heartbeat and any noises made by your digestive system.

You might feel fine now, but as your bump grows, you will become more unstable on your feet.";
    }elseif ($weeks ==16) {
        $week_image = '../assets/images/weeks/16.png';
         $week_text  = "Your baby, or foetus, is around 11.6cm long from head to bottom, which is the size of an avocado.
Your baby is starting to pull faces now, but any smiling or frowning will be completely random, as there's no muscle control yet.

Your baby is growing quickly and about to have another growth spurt. You will probably have put on some weight over the past few weeks too.";
    }elseif ($weeks ==17) {
        $week_image = '../assets/images/weeks/17.png';
         $week_text  = "Your baby, or foetus, is around 12cm long, from head to bottom. That's roughly the size of a pomegranate.
         Your baby's fingernails are starting to grow and will have their own unique fingerprints. Even identical twins have different fingerprints.

         You might start to feel your baby move now. You will not be able to tell exactly what they're up to, but soon you could be 
         feeling every kick, punch, hiccup and somersault.";
    }elseif ($weeks ==18) {
        $week_image = '../assets/images/weeks/18.png';
         $week_text  = "Your baby, or foetus, is around 14.2cm long from head to bottom. That's approximately the size of a bell pepper.
Your baby's hearing, feeling, swallowing and sucking reflexes are developing this week.
They will also be doing a lot of wriggling around and moving their arms and legs.

You might be starting to feel a bit clumsier as your belly gets bigger. Your breasts may have gone up a size, too, particularly if it's your first pregnancy.
Your blood pressure is probably a bit lower than it was, so do not leap up from the sofa, or it could make you feel dizzy.";
    }elseif ($weeks ==19) {
        $week_image = '../assets/images/weeks/19.png';
         $week_text  = "It's great for you and baby to stay active, but some exercises, such as running, could become uncomfortable.
This is because the hormone loosens up your ligaments, leaving your back, knees and ankles without their usual support.
         
         Your baby, or foetus, is around 15.3cm long from head to bottom. That's approximately the size of a beef tomato.
Their adult teeth are starting to grow, and they are lining up behind the first set.";
    }elseif ($weeks ==20) {
        $week_image = '../assets/images/weeks/20.png';
         $week_text  = "Your baby, or foetus, is around 25.6cm long. That's approximately the size of a banana.They are now covered in a white, greasy layer called vernix. It's thought this protects their delicate skin from drying out in the amniotic fluid. This slippery layer also helps babies to make their way down the birth canal.
         
         You might find yourself being woken up at night by sudden sharp pains in your calves.It's probably cramp, which is common in pregnancy and caused by muscular spasms. Rub the muscle hard or pull your toes up towards your ankle.";
    }elseif ($weeks ==21) {
        $week_image = '../assets/images/weeks/21.png';
         $week_text  = "Your baby, or foetus, is around 26.7cm long from head to toe. That's approximately the size of a carrot.
         Your baby is now heavier than the placenta.
         
         As you start the 2nd half of your pregnancy, you'll be entering a period of rapid growth.
Your baby is getting ready for life outside the womb and developing essential skills including sucking and breathing.";
    }elseif ($weeks ==22) {
        $week_image = '../assets/images/weeks/22.png';
          $week_text  = "This is a good week to talk to your bump, sing to it, caress it with moisturiser and you might even feel a little flutter in response. 
          It's all part of bonding with your unborn baby who, as you can probably tell, is getting bigger by the day.
          
          Your baby, or foetus, is around 27.8cm long from head to toe. That's approximately the size of a sweet potato.
The lungs are developing and your little one will be doing some breathing practice in your womb.Your baby's taste buds are developing and could be influenced by what you eat. 
Try to eat healthily and include lots of fresh fruit and veg.";
    }elseif ($weeks ==23) {
        $week_image = '../assets/images/weeks/23.png';
         $week_text  = "This week, you may start to get rib pain as your rib cage expands to accommodate your bump. You could be feeling a bit more breathless than usual as the growing baby puts pressure on your lungs.
         
         Your baby, or foetus, is around 28.9cm long from head to heel. That's approximately the size of a large mango.
Your baby's limbs are now in proportion. Over the next few weeks, you're going to be kicked around by your baby and will start to see your tummy move too,
 which looks very strange.";
    }elseif ($weeks ==24) {
        $week_image = '../assets/images/weeks/24.png';
         $week_text  = "Your baby, or foetus, is around 30cm long from head to heel. That's approximately the size of a corn on the cob.
         If your baby was born now, there is a chance they will survive outside the womb. Baby units for premature babies can help them breathe, feed, keep warm and fight infections.
         
         Around now, you could be getting pains around your ribs, back, breasts, bottom, stomach, etc. This is partly due to your pregnancy hormones loosening up your ligaments and muscles, 
         and your growing baby pushing on various parts of your body.";
    }elseif ($weeks ==25) {
        $week_image = '../assets/images/weeks/25.png';
         $week_text  = "Your baby, or foetus, is around 34.6cm long from head to heel. That's approximately the size of a courgette.
This is an active time for your baby. A loud noise could make your baby jump and kick, which should not hurt but might take you by surprise. You might even feel the occasional hiccup.
         
         You could be starting to get a bit puffy and swollen in your face, hands and feet.
This is probably completely harmless and caused by water retention – but do mention it to your midwife or doctor. They will want to check your blood pressure, just in case it's a sign of a dangerous condition called pre-eclampsia.";
    }elseif ($weeks ==26) {
        $week_image = '../assets/images/weeks/26.png';
         $week_text  = "As you approach the 3rd trimester, you might be feeling more tired, and a bit more clumsy and uncoordinated.
         You may need to allow yourself more time to do your usual activities, like your daily walk to the bus stop.
It's important to stay active but your body's changing all the time, so be patient with yourself.
         
Your baby, or foetus, is around 35.6cm long from head to heel. That's approximately the size of a cucumber.
Around now, your baby's eyes will be opening for the first time and the next trick will be to learn how to blink.";
    }elseif ($weeks ==27) {
        $week_image = '../assets/images/weeks/27.png';
         $week_text  = "Your baby, or foetus, is around 36.6cm long from head to heel. That's approximately size of a head of cauliflower.
         A few weeks ago, your baby looked a bit like a wrinkled prune. Now the folds of skin are being filled out by fat, and all their organs are maturing, as your baby prepares for life outside the uterus (womb).
         
         You're probably putting on a few pounds now, and may be feeling bloated and constipated.
This is partly because your stomach is being squeezed by your growing baby, and partly due to the pregnancy hormone, progesterone.
It might help to drink lots of water, choose high-fibre options (like brown bread and wholewheat pasta, rather than white) and eat lots of fresh fruit and veg.";
    }elseif ($weeks ==28) {
        $week_image = '../assets/images/weeks/28.png';
         $week_text  = "
         Your baby, or foetus, is around 37.6cm long from head to heel. That's approximately the size of an aubergine.
Your baby's heart rate is changing all the time. Around week 5 or 6, when a baby's heart is first detectable, it is around 110 beats per minute (bpm). Then it goes up to around 170 bpm in week 9 and 10.
Now, it's slowed down to around 140 bpm and it will be around 130 bpm at birth.

You may be getting a bit of heartburn and indigestion. That's down to your growing baby and hormones affecting your digestive system.
Your back will also be under strain, due to the extra weight you're carrying around. Your joints and ligaments will also be looser than usual.
Your ankles, feet and face could be puffing out a bit, particularly when it's hot.";
    }elseif ($weeks ==29) {
        $week_image = '../assets/images/weeks/29.png';
         $week_text  = "Your baby, or foetus, is around 38.6cm long from head to heel. That's approximately the size of a butternut squash.
Your baby is now perfectly formed – over the next few weeks, they have lots to do, like maturing their organs and gaining fat.

You might be feeling a bit breathless, as your baby pushes against your lungs. It puts a strain on your body carrying extra weight around too.
You may feel irritated when people tell you to enjoy your sleep while you can, as it's not very easy right now.";
    }elseif ($weeks ==30) {
        $week_image = '../assets/images/weeks/30.png';
         $week_text  = "Your baby, or foetus, is around 39.9cm long from head to heel. That's approximately the size of a cabbage.
Your baby's eyes can now focus. Their vision will continue to develop inside and outside the uterus (womb).
         
         You may be having trouble sleeping and then when you do, you may be having disturbing dreams.
Try to remember these dreams are not real. They are fuelled by your hormones and the anxiety that you're probably feeling about the big changes ahead.";
    }elseif ($weeks ==31) {
        $week_image = '../assets/images/weeks/31.png';
         $week_text  = "Your baby, or foetus, is around 41.1cm long from head to heel. That's approximately the size and weight of a coconut.
         Day by day, your baby is getting plumper and looking less wrinkled. The amount of amniotic fluid surrounding your baby is increasing because your baby is now able to pee.
         
         Your baby and bump are still growing.In a couple of weeks, you will both go through a final growth spurt. Your baby still has lots of fattening up to do before the big day arrives.";
    }elseif ($weeks ==32) {
        $week_image = '../assets/images/weeks/32.png';
         $week_text  = "Your baby, or foetus, is around 42.4cm long from head to heel. That's about the same length as a bunch of celery.
Your baby is perfectly formed but needs to put on weight – that's what the next few weeks are all about.

You might find your bump is making it harder to walk and making you waddle. That's your body's way of compensating for all that extra weight up front.
";
    }elseif ($weeks ==33) {
        $week_image = '../assets/images/weeks/33.png';
         $week_text  = "Your baby, or foetus, is around 43.7cm long from head to heel. That's approximately the size of a pineapple.
Your baby's brain and nervous system are now fully developed.The bones are hardening up, apart from the skull bones.

Your uterus (womb) could start preparing for the birth with Braxton Hicks contractions, which are sometimes referred to as practice contractions.
These can feel like a tightening over your bump for 20 to 30 seconds, before the muscles relax again.
It shouldn't hurt, but if the contractions become painful or begin to happen at regular intervals, contact your midwife or hospital, in case you're going into labour.
";
    }elseif ($weeks ==34) {
        $week_image = '../assets/images/weeks/34.png';
         $week_text  = "Your baby, or foetus, is around 45cm long from head to heel. That's approximately the size of a cantaloupe melon.
Your baby is curled up inside your uterus (womb), with their little legs bent up towards the chest.

Perhaps it feels as though some of your pregnancy symptoms have vanished. This can happen when your baby moves head down into the pelvis.
This shift may feel like a relief, but it does not mean you're about to give birth, as you'll probably have to wait several more weeks for that to happen.";
    }elseif ($weeks ==35) {
        $week_image = '../assets/images/weeks/35.png';
         $week_text  = "Your baby is around 46.2cm long from head to heel. That's approximately the size of a honeydew melon.
Your baby is getting chubbier, which will help them to stay at the right temperature when they're born.

If you've spotted any yellow stains in your bra, then that's probably colostrum, which is an early milk that is rich in antibodies.
Some pregnant women start to make it weeks or even months before the birth. When you breastfeed, this helps to protect your baby from stomach bugs and other infections.";
    }elseif ($weeks ==36) {
        $week_image = '../assets/images/weeks/36.png';
         $week_text  = "Your baby, or foetus, is around 47.4cm long from head to heel. That's approximately the size of a romaine lettuce.
By now, your baby's lungs are probably mature enough to breathe outside the womb without any help.
         
If your baby's not head down yet, then you may be offered external cephalic version (ECV).
         This is where your doctor or midwife gently applies a helping hand to your bump to encourage the baby to turn – it's successful around half the time.";
    }elseif ($weeks ==37) {
        $week_image = '../assets/images/weeks/37.png';
         $week_text  = "Your baby, or foetus, is around 48.6cm long from head to heel. That's approximately the length of a leek.
Your baby will be trying out different facial expressions, such as frowning and smiling. This is random and not linked to sadness or happiness.
By now, you will hopefully know when your baby's active and when they're calmer.
         
Your baby is now full term, which means that they're probably big enough, and mature enough, to survive in the outside world. However, you still may have to wait another few weeks.";
    }elseif ($weeks ==38) {
        $week_image = '../assets/images/weeks/38.png';
         $week_text  = "Your baby, or foetus, is around 49.8cm long from head to heel. That's approximately the length of a stick of rhubarb.
In the 2nd trimester, your baby was covered in a furry coat of soft, downy hair (lanugo). That's mostly gone now, although some babies are born with patches here and there.

If you're having a planned caesarean, otherwise known as an elective caesarean, then you'll probably be booked in when you're at least 39 weeks' pregnant. This is to give your baby's lungs the best chance of being fully developed.";
    }elseif ($weeks ==39) {
        $week_image = '../assets/images/weeks/39.png';
         $week_text  = "Your baby, or foetus, is around 50.7cm long from head to heel. That's approximately the size of a watermelon.
A few weeks ago, your baby's skin was almost transparent but now they're growing a tougher new layer that looks more solid. This is better at protecting their internal organs and helping with temperature control.

If you spot a slimy blob of mucus that's yellow or bloody, then that's called a show. This sticky stuff used to plug up your cervix and when it comes out, it can be one of the first signs that your baby's on the way.";
    }elseif ($weeks ==40) {
        $week_image = '../assets/images/weeks/40.png';
         $week_text  = "Your baby, or foetus, is around 51.2cm long from head to heel. That's approximately the size of a pumpkin.
Your baby is getting rather squashed up now, but should still be moving around in their usual pattern. Movements should not slow down or stop, and if they do, it could be an important sign that something is wrong.
         
         The wait is nearly over. Within days, you'll get to meet your baby. It's been quite a journey, but the real adventure starts when your little one is born.";
    }elseif ($weeks ==41) {
        $week_image = '../assets/images/weeks/40.png';
         $week_text  = "The average baby is now around 3 to 4kg.
         Overdue babies tend to have red, dry and peeling skin. This is usually because they've lost their vernix, which is the greasy layer that stops their skin from drying out in the amniotic fluid.
         
         At 41 weeks pregnant, your baby is taking a little extra time. Your midwife or doctor will check in with you and talk about the next steps, to help your baby arrive safely.";
        }
    
     else {
        $week_image = '../assets/images/weeks/chart.png';
    }

    //  Display the week image
    echo '<div class="week-image mt-3 text-center">';

    // Image
    echo '<img src="'.htmlspecialchars($week_image).'" alt="Week '.$weeks.'" style="width:70%; max-width:250px; height:auto; border-radius:8px;">';
    
    // Text
    echo '<p style="margin-top:15px; font-size:16px; line-height:1.6; color:#333; font-family:Arial, sans-serif;">';
    echo nl2br(htmlspecialchars($week_text)); // nl2br keeps line breaks
    echo '</p>';

echo '</div>';


?>
<br>
 <em>Please log your symptoms!</em>
</div>
</div>
</div>
<div class="insights">
    <?php include('insights.php'); ?>
</div>

<!-- Symptom Modal -->
<button type="button" id="openSymptomModal" title="Log your symptoms" style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; font-size: 36px; border-radius: 50%; border:none; background:rgb(246,224,209); color:red; cursor:pointer; box-shadow:0 4px 8px rgba(0,0,0,0.8); z-index:1050;">+</button>
<div class="modal fade" id="symptomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Log your Symptom</h5>
                 
               
            </div>
            <div class="modal-body">
             
                <form id="symptomForm">
                  <label>Date</label> <input type="date" id="symptomDate" name="symptom_date" class="form-control" required>
                    <textarea name="symptoms_text" rows="3" class="form-control" placeholder="How are you feeling today"></textarea>
                    <div id="diagnosisBox" class="mt-3 p-2 border rounded bg-light"></div>
                     
                      <button type="submit" class="btn btn-primary mt-2" data-bs-dismiss="modal">Submit</button>
                      <button type="button" class="btn-close ms-4 mt-2" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- View Symptoms Modal -->
<div class="modal fade" id="viewSymptomsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Symptoms on <span id="viewDate"></span></h5>
      </div>
        
      <div class="modal-body" id="viewSymptomsContent">Loading...</div>
    </div>
    <hr>
     <button type="button" class="btn-close" data-bs-dismiss="modal">Close</button>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
     const symptomModal = new bootstrap.Modal(document.getElementById('symptomModal')); 
     const symptomDateInput = document.getElementById('symptomDate');

    // Show symptom modal button
    document.getElementById('openSymptomModal').addEventListener('click', () => symptomModal.show());
     const today = new Date();
    if(symptomDateInput){
        symptomDateInput.max = today.toISOString().split('T')[0]; // future blocked
        const lastPeriod = "<?= $last_period ?>";
        if(lastPeriod){
            symptomDateInput.min = lastPeriod; // cannot select before last period
            symptomDateInput.value = today.toISOString().split('T')[0]; // default today
        }
    }

    // FullCalendar
  
const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
    initialView: 'dayGridMonth',
    height: 500,
    headerToolbar: { left:'', center:'title', right:'prev,next' },
    
    
    // Style invalid dates to make them visually distinct (faded but visible)
    dayCellDidMount: function(info) {
        const today = new Date();
        const nineMonthsAgo = new Date();
        nineMonthsAgo.setMonth(nineMonthsAgo.getMonth() - 9);
        
        const cellDate = info.date;
        const lastPeriodValue = "<?= $last_period ?>";
        
        // Determine if date is invalid
        let isInvalid = false;
        
        // Check if date is in future
        if (cellDate > today) {
            isInvalid = true;
        }
        
        // Check if date is older than 9 months
        if (cellDate < nineMonthsAgo) {
            isInvalid = true;
        }
        
        // Check if date is before last period
        if (lastPeriodValue && cellDate < new Date(lastPeriodValue)) {
            isInvalid = true;
        }
        
        // Style invalid dates as faded but still visible
        if (isInvalid) {
            info.el.style.backgroundColor = '#f9f9f9';
            info.el.style.color = '#bbb';
            info.el.style.cursor = 'not-allowed';
            info.el.style.opacity = '0.6';
           
        }
    },
    
    dateClick: function(info) {
        const clickedDate = new Date(info.dateStr);
        const today = new Date();
        const nineMonthsAgo = new Date();
        nineMonthsAgo.setMonth(nineMonthsAgo.getMonth() - 9);
        const lastPeriodValue = "<?= $last_period ?>";
        
        // Prevent interaction with invalid dates
        if (clickedDate > today) {
            alert('You cannot select a future date.');
            return;
        }
        
        if (clickedDate < nineMonthsAgo) {
            alert('You cannot select a date older than 9 months.');
            return;
        }
        
        if (lastPeriodValue && clickedDate < new Date(lastPeriodValue)) {
            alert('You cannot select a date before your pregnancy date.');
            return;
        }
        
         // Show modal
    const modalEl = document.getElementById('viewSymptomsModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    document.getElementById('viewDate').textContent = info.dateStr;
        
    fetch('../user/get_symptom.php?date=' + info.dateStr, { credentials: 'same-origin' })
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = '<p>No symptoms logged for this date.</p>';
            } else {
                data.forEach(d => {
                    html += `<div class="mb-3">
                                <p><strong>Symptoms:</strong> ${d.symptoms_text}</p>
                                ${d.diagnosis_html || ''}
                                <small class="text-muted">Logged at: ${d.created_at}</small>
                             </div>`;
                });
            }
              const container = document.getElementById('viewSymptomsContent');
        container.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            document.getElementById('viewSymptomsContent').innerHTML = '<p>Error loading data.</p>';
        });
}
});

calendar.render();

//submit symptom
document.getElementById('symptomForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('../user/diagnosis_symptom.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            document.getElementById('diagnosisweek').innerHTML = data.diagnosis;
            symptomModal.hide(); // close modal safely
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error connecting to server.');
    });
});





  
    // // Set symptom modal date limits based on last_period
    if(lastPeriodValue){
        symptomDateInput.min = lastPeriodValue;
        symptomDateInput.max = today.toISOString().split('T')[0];
        symptomDateInput.value = today.toISOString().split('T')[0];
    }
    
});

</script>

</body>

</html>

<?php

namespace Database\Seeders;

use App\Models\DefaultKeyword;
use App\Models\LanguageList;
use App\Models\LanguageWithKeyword;
use Illuminate\Database\Seeder;
use App\Models\Screen;

class ScreenkeywordSeeder extends Seeder
{

  /**
   * Auto generated seed file
   *
   * @return void
   */
  public function run()
  {
    $languageListIds = LanguageList::pluck('id')->toArray();
    $fetchedKeywords = LanguageWithKeyword::whereIn('language_id', $languageListIds)
    ->pluck('keyword_id')
    ->toArray();
    
    $screen_data = 
    [
      [
        "screen_id"=> "1",
        "screen_name"=> "SplashScreen",
        "keyword_data"=> [
          [
            "screenId"=> "1",
            "keyword_id"=> 1,
            "keyword_name"=> "warning",
            "keyword_value"=> "Warning"
          ],
          [
            "screenId"=> "1",
            "keyword_id"=> 2,
            "keyword_name"=> "warningDisclaimer",
            "keyword_value"=> "app is not a diagnostic tools. All data, images, videos and other content are just for demonstration or educational purposes only"
          ],
          [
            "screenId"=> "1",
            "keyword_id"=> 3,
            "keyword_name"=> "okayIUnderstand",
            "keyword_value"=> "Okay I Understand"
          ],
          [
            "screenId"=> "1",
            "keyword_id"=> 4,
            "keyword_name"=> "doNotShowDialog",
            "keyword_value"=> "Do not show warning Dialog Again"
          ]
        ]
      ],
      [
        "screenID"=> "2",
        "ScreenName"=> "policyScreen",
        "keyword_data"=> [
          [
            "screenId"=> "2",
            "keyword_id"=> 6,
            "keyword_name"=> "youAndEra",
            "keyword_value"=> "You and"
          ],
          [
            "screenId"=> "2",
            "keyword_id"=> 7,
            "keyword_name"=> "policyDeclaration",
            "keyword_value"=> "We promise to keep your data safe, secure, and private.\nPlease take a moment to familiarize yourself with our policies to learn more about how we protect your information."
          ],
          [
            "screenId"=> "2",
            "keyword_id"=> 8,
            "keyword_name"=> "iAgreeTo",
            "keyword_value"=> "I agree to"
          ],
          [
            "screenId"=> "2",
            "keyword_id"=> 9,
            "keyword_name"=> "iHaveRead",
            "keyword_value"=> "I have read"
          ],
          [
            "screenId"=> "2",
            "keyword_id"=> 11,
            "keyword_name"=> "processingHealthData",
            "keyword_value"=> "processing the health data I choose to share with the app, so they can provide their service"
          ],
          [
            "screenId"=> "2",
            "keyword_id"=> 12,
            "keyword_name"=> "next",
            "keyword_value"=> "Next"
          ]
        ]
      ],
      [
        "screenID"=> "3",
        "ScreenName"=> "questionScreen",
        "keyword_data"=> [
          [
            "screenId"=> "3",
            "keyword_id"=> 13,
            "keyword_name"=> "areYouUsing",
            "keyword_value"=> "Are you using"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 14,
            "keyword_name"=> "forYourself",
            "keyword_value"=> "for yourself"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 16,
            "keyword_name"=> "yesForTracking",
            "keyword_value"=> "Yes, For Tracking"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 17,
            "keyword_name"=> "yesAsADoctor",
            "keyword_value"=> "Yes, As a Doctor"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 18,
            "keyword_name"=> "alreadyHaveAnAccount",
            "keyword_value"=> "Already have an account? Login now"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 19,
            "keyword_name"=> "whatIsYourGoalType",
            "keyword_value"=> "What is your Goal Type"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 20,
            "keyword_name"=> "allFeatureswWillBeAvailable",
            "keyword_value"=> "All features will be available regardless of your selection"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 21,
            "keyword_name"=> "trackCycle",
            "keyword_value"=> "Track Cycle"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 22,
            "keyword_name"=> "stayPreparedForYourNextPeriod",
            "keyword_value"=> "Stay prepared for your next period and gain insights into your body with statistics on previous cycles"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 23,
            "keyword_name"=> "trackPregnancy",
            "keyword_value"=> "Track Pregnancy"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 24,
            "keyword_name"=> "monitorChangesInYourBody",
            "keyword_value"=> "Monitor changes in body weight and log your well-being throughout your pregnancy journey"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 25,
            "keyword_name"=> "whenDidYourLastPeriod",
            "keyword_value"=> "When did your last period start?"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 26,
            "keyword_name"=> "provideThisInformationSoThatWeCanPredict",
            "keyword_value"=> "Provide this information so we can predict your next period"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 27,
            "keyword_name"=> "continue",
            "keyword_value"=> "Continue"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 28,
            "keyword_name"=> "skip",
            "keyword_value"=> "Skip"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 29,
            "keyword_name"=> "select",
            "keyword_value"=> "Select"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 30,
            "keyword_name"=> "whatIsYourCycleLength",
            "keyword_value"=> "What is your cycle Length"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 31,
            "keyword_name"=> "whatIsYourPeriodDuration",
            "keyword_value"=> "What is your period duration"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 32,
            "keyword_name"=> "whatIsYourPeriodDurationDescription",
            "keyword_value"=> "The app uses cycle and period length settings to make predictions. For more accurate predictions, regularly log your period data in the app"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 33,
            "keyword_name"=> "keepYourHealthDataSafe",
            "keyword_value"=> "Keep your health data safe"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 34,
            "keyword_name"=> "createYourAccountToSaveInformation",
            "keyword_value"=> "Create your account to save your information securely"
          ],
          [
            "screenId"=> "3",
            "keyword_id"=> 35,
            "keyword_name"=> "iWillRegisterLater",
            "keyword_value"=> "I'll register later"
          ]
        ]
      ],
      [
        "screenID"=> "4",
        "ScreenName"=> "progressScreen",
        "keyword_data"=> [
          [
            "screenId"=> "4",
            "keyword_id"=> 36,
            "keyword_name"=> "progressScreenMainText",
            "keyword_value"=> "Explore and discover the information you seek in privacy and confidence"
          ],
          [
            "screenId"=> "4",
            "keyword_id"=> 37,
            "keyword_name"=> "personalizeYourExperience",
            "keyword_value"=> "Personalize your experience..."
          ]
        ]
      ],
      [
        "screenID"=> "5",
        "ScreenName"=> "homeScreen",
        "keyword_data"=> [
          [
            "screenId"=> "5",
            "keyword_id"=> 38,
            "keyword_name"=> "todayActivity",
            "keyword_value"=> "Today's Activity"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 39,
            "keyword_name"=> "thereAreNoDataAvailable",
            "keyword_value"=> "There are no data available to analyze your cycle"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 40,
            "keyword_name"=> "thereAreNoDataAvailableText",
            "keyword_value"=> "Please log at least 2 to 3 periods to unlock personalised insights and track your cycle regularly"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 41,
            "keyword_name"=> "logPreviousCycle",
            "keyword_value"=> "Log Previous Cycle"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 42,
            "keyword_name"=> "topTipsForYou",
            "keyword_value"=> "Top tips for you"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 43,
            "keyword_name"=> "basedOnYour",
            "keyword_value"=> "Based on your"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 44,
            "keyword_name"=> "cycle",
            "keyword_value"=> "cycle"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 45,
            "keyword_name"=> "myCycle",
            "keyword_value"=> "My Cycle"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 46,
            "keyword_name"=> "previousCycleLength",
            "keyword_value"=> "Previous Cycle Length"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 47,
            "keyword_name"=> "previousPeriodLength",
            "keyword_value"=> "Previous Period Length"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 48,
            "keyword_name"=> "cycleTrends",
            "keyword_value"=> "Cycle Trends"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 49,
            "keyword_name"=> "cycleHistory",
            "keyword_value"=> "Cycle History"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 50,
            "keyword_name"=> "cyclePeriod",
            "keyword_value"=> "Cycle Period"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 51,
            "keyword_name"=> "note",
            "keyword_value"=> "Note"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 52,
            "keyword_name"=> "isNotADiagnosticTool",
            "keyword_value"=> "is not a diagnostic tool"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 152,
            "keyword_name"=> "todayPredictedSymptoms",
            "keyword_value"=> "Today's Predicted Symptoms"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 153,
            "keyword_name"=> "tomorrowYouMayFeel",
            "keyword_value"=> "Tomorrow you may feel"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 154,
            "keyword_name"=> "nextPeriodDate",
            "keyword_value"=> "Next Period Date"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 155,
            "keyword_name"=> "nextOvulationDate",
            "keyword_value"=> "Next Ovulation Date"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 172,
            "keyword_name"=> "logPeriod",
            "keyword_value"=> "Log Period"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 173,
            "keyword_name"=> "minRead",
            "keyword_value"=> "min read"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 373,
            "keyword_name"=> "details",
            "keyword_value"=> "Details"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 374,
            "keyword_name"=> "yourQuestions",
            "keyword_value"=> "YourQuestions"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 375,
            "keyword_name"=> "day",
            "keyword_value"=> "Day"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 410,
            "keyword_name"=> "backupFound",
            "keyword_value"=> "Backup Found!"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 411,
            "keyword_name"=> "backupFoundDescription",
            "keyword_value"=> "Whoa! We found a backup. Do you want to restore it?\\n\\nNote=> Restoring will restart the app"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 412,
            "keyword_name"=> "backupFoundDescription",
            "keyword_value"=> "Whoa! We found a backup. Do you want to restore it? Note=> Restoring will restart the app"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 413,
            "keyword_name"=> "noIDont",
            "keyword_value"=> "No, I don't"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 414,
            "keyword_name"=> "backupRestoredSuccess",
            "keyword_value"=> "Backup restored successfully!"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 415,
            "keyword_name"=> "yesRestore",
            "keyword_value"=> "Yes, Restore"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 416,
            "keyword_name"=> "less",
            "keyword_value"=> "Less"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 417,
            "keyword_name"=> "more",
            "keyword_value"=> "More"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 418,
            "keyword_name"=> "upcomingAppointments",
            "keyword_value"=> "Upcoming Appointments"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 419,
            "keyword_name"=> "today",
            "keyword_value"=> "Today"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 419,
            "keyword_name"=> "tomorrow",
            "keyword_value"=> "Tomorrow"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 420,
            "keyword_name"=> "upcomingEducationSession",
            "keyword_value"=> "Upcoming Education Session"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 421,
            "keyword_name"=> "statusOfCycleDay",
            "keyword_value"=> "Status of Cycle Day"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 422,
            "keyword_name"=> "yourBabyAtWeek",
            "keyword_value"=> "Your Baby at Week"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 423,
            "keyword_name"=> "notAvailable",
            "keyword_value"=> "Not available"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 424,
            "keyword_name"=> "noGrowthInformationAvailable",
            "keyword_value"=> "No growth information available"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 425,
            "keyword_name"=> "dueDate",
            "keyword_value"=> "Due date"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 488,
            "keyword_name"=> "Categories",
            "keyword_value"=> "Categories"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 546,
            "keyword_name"=> "online",
            "keyword_value"=> "Online"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 547,
            "keyword_name"=> "Case",
            "keyword_value"=> "Case"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 548,
            "keyword_name"=> "PaymentMethod",
            "keyword_value"=> "Payment Method"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 549,
            "keyword_name"=> "AvailableTime",
            "keyword_value"=> "Available Time"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 550,
            "keyword_name"=> "symptomsOrReasonForVisit",
            "keyword_value"=> "Symptoms or Reason for visit..."
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 551,
            "keyword_name"=> "Change",
            "keyword_value"=> "Change"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 552,
            "keyword_name"=> "Payment",
            "keyword_value"=> "Payment"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 553,
            "keyword_name"=> "Amount",
            "keyword_value"=> "Amount"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 561,
            "keyword_name"=> "PayNow",
            "keyword_value"=> "Pay Now"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 554,
            "keyword_name"=> "AppointmentOn",
            "keyword_value"=> "Appointment On"
          ],
          [
            "screenId"=> "5",
            "keyword_id"=> 555,
            "keyword_name"=> "AppointmentDetail",
            "keyword_value"=> "Appointment Detail"
          ]
        ]
      ],
      [
        "screenID"=> "6",
        "ScreenName"=> "graphAndReports",
        "keyword_data"=> [
          [
            "screenId"=> "6",
            "keyword_id"=> 53,
            "keyword_name"=> "graphsAndReport",
            "keyword_value"=> "Graphs & Report"
          ],
          [
            "screenId"=> "6",
            "keyword_id"=> 54,
            "keyword_name"=> "periodLength",
            "keyword_value"=> "Period Length"
          ],
          [
            "screenId"=> "6",
            "keyword_id"=> 55,
            "keyword_name"=> "periodLength",
            "keyword_value"=> "Period Length"
          ],
          [
            "screenId"=> "6",
            "keyword_id"=> 56,
            "keyword_name"=> "waterInformation",
            "keyword_value"=> "Water Information"
          ],
          [
            "screenId"=> "6",
            "keyword_id"=> 57,
            "keyword_name"=> "basalTemperature",
            "keyword_value"=> "Basal Temperature"
          ],
          [
            "screenId"=> "6",
            "keyword_id"=> 58,
            "keyword_name"=> "meditation",
            "keyword_value"=> "Meditation"
          ],
          [
            "screenId"=> "6",
            "keyword_id"=> 59,
            "keyword_name"=> "weight",
            "keyword_value"=> "Weight"
          ]
        ]
      ],
      [
        "screenID"=> "7",
        "ScreenName"=> "settingsScreen",
        "keyword_data"=> [
          [
            "screenId"=> "7",
            "keyword_id"=> 60,
            "keyword_name"=> "settings",
            "keyword_value"=> "Settings"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 61,
            "keyword_name"=> "myGoal",
            "keyword_value"=> "My goal"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 62,
            "keyword_name"=> "periodPrediction",
            "keyword_value"=> "Period Prediction"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 63,
            "keyword_name"=> "pregnancy",
            "keyword_value"=> "Pregnancy"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 64,
            "keyword_name"=> "addDummyData",
            "keyword_value"=> "Add Dummy Data"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 65,
            "keyword_name"=> "reminders",
            "keyword_value"=> "Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 66,
            "keyword_name"=> "cycleReminders",
            "keyword_value"=> "Cycle Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 67,
            "keyword_name"=> "medicineReminders",
            "keyword_value"=> "Medicine Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 68,
            "keyword_name"=> "meditationReminders",
            "keyword_value"=> "Meditation Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 470,
            "keyword_name"=> "toSetFingerPrintAuthenticationFirstSetPinAuthentication",
            "keyword_value"=> "To set finger print authentication first set pin authentication"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 69,
            "keyword_name"=> "secureAccess",
            "keyword_value"=> "Secure Access (PIN)"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 70,
            "keyword_name"=> "dailyLoggingReminders",
            "keyword_value"=> "Daily Logging Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 71,
            "keyword_name"=> "trackingReminders",
            "keyword_value"=> "Tracking Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 72,
            "keyword_name"=> "secretReminders",
            "keyword_value"=> "Secret Reminders"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 73,
            "keyword_name"=> "post",
            "keyword_value"=> "Post"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 74,
            "keyword_name"=> "myPersonal",
            "keyword_value"=> "My Personal"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 75,
            "keyword_name"=> "yourName",
            "keyword_value"=> "Your Name"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 76,
            "keyword_name"=> "languages",
            "keyword_value"=> "Languages"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 77,
            "keyword_name"=> "subscription",
            "keyword_value"=> "Subscription"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 78,
            "keyword_name"=> "myData",
            "keyword_value"=> "My Data"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 79,
            "keyword_name"=> "backUpData",
            "keyword_value"=> "Back Up Data"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 80,
            "keyword_name"=> "restoreData",
            "keyword_value"=> "Restore Data"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 81,
            "keyword_name"=> "deleteAppData",
            "keyword_value"=> "Delete App Data"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 82,
            "keyword_name"=> "others",
            "keyword_value"=> "Others"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 83,
            "keyword_name"=> "calculatorTools",
            "keyword_value"=> "Calculator Tools"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 84,
            "keyword_name"=> "educationalSession",
            "keyword_value"=> "Educational Session"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 85,
            "keyword_name"=> "bookmark",
            "keyword_value"=> "Bookmark"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 86,
            "keyword_name"=> "accessCode",
            "keyword_value"=> "Access Code"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 87,
            "keyword_name"=> "bookAppointment",
            "keyword_value"=> "Book Appointment"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 88,
            "keyword_name"=> "rate",
            "keyword_value"=> "Rate"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 89,
            "keyword_name"=> "share",
            "keyword_value"=> "Share"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 90,
            "keyword_name"=> "about",
            "keyword_value"=> "About"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 91,
            "keyword_name"=> "faq",
            "keyword_value"=> "FAQ"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 332,
            "keyword_name"=> "noFAQsFound",
            "keyword_value"=> "No FAQs found"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 333,
            "keyword_name"=> "NoteTapOntoAnswer",
            "keyword_value"=> "Note=> Tap onto answer to read full blog"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 334,
            "keyword_name"=> "AnsweredBy",
            "keyword_value"=> "Answered by"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 92,
            "keyword_name"=> "helpAndSupport",
            "keyword_value"=> "Help and Support"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 93,
            "keyword_name"=> "deleteAccount",
            "keyword_value"=> "Delete Account"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 94,
            "keyword_name"=> "logout",
            "keyword_value"=> "Logout"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 438,
            "keyword_name"=> "noDescriptionAvailable",
            "keyword_value"=> "No description available"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 439,
            "keyword_name"=> "noEmailAvailable",
            "keyword_value"=> "No email available"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 440,
            "keyword_name"=> "contactSupport",
            "keyword_value"=> "Contact Support"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 441,
            "keyword_name"=> "noNumberAvailable",
            "keyword_value"=> "No number available"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 442,
            "keyword_name"=> "followUs",
            "keyword_value"=> "Follow Us"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 449,
            "keyword_name"=> "areYouSureYouWantToDeleteYourData",
            "keyword_value"=> "Are you sure you want to delete your data?"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 450,
            "keyword_name"=> "dataCleared",
            "keyword_value"=> "Data cleared."
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 451,
            "keyword_name"=> "internetRequiredForThisAction",
            "keyword_value"=> "Internet required for this action."
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 452,
            "keyword_name"=> "n0DoNot",
            "keyword_value"=> "No, Don’t"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 453,
            "keyword_name"=> "restoreDataFrom",
            "keyword_value"=> "Restore data from"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 454,
            "keyword_name"=> "dataRestored",
            "keyword_value"=> "Data Restored."
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 455,
            "keyword_name"=> "retrievingYourData",
            "keyword_value"=> "Retrieving your data..."
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 493,
            "keyword_name"=> "NoDataAvailableToGenerateYourCycleReminder",
            "keyword_value"=> "No data available to generate your cycle reminder"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 494,
            "keyword_name"=> "PleaseLogYourDataToGenerateCycleReminder",
            "keyword_value"=> "Please log your data to generate cycle reminder"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 495,
            "keyword_name"=> "nextPeriod",
            "keyword_value"=> "Next Period"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 496,
            "keyword_name"=> "nextOvulation",
            "keyword_value"=> "Next Ovulation"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 497,
            "keyword_name"=> "EditTime",
            "keyword_value"=> "Edit Time"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 498,
            "keyword_name"=> "pleaseSelectATime",
            "keyword_value"=> "Please select a time"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 499,
            "keyword_name"=> "ReminderSetSuccessfully",
            "keyword_value"=> "Reminder Set Successfully!"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 500,
            "keyword_name"=> "PleaseEnterMessageForReminder",
            "keyword_value"=> "Please enter message for reminder"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 501,
            "keyword_name"=> "ViewAll",
            "keyword_value"=> "View All"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 502,
            "keyword_name"=> "couldNotLoadOvulationDate",
            "keyword_value"=> "Could not load ovulation date"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 503,
            "keyword_name"=> "mayBelater",
            "keyword_value"=> "Maybe later"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 504,
            "keyword_name"=> "Your",
            "keyword_value"=> "Your"
          ],
          [
            "screenId"=> "7",
            "keyword_id"=> 505,
            "keyword_name"=> "reminderIsSetAt",
            "keyword_value"=> "Reminder is set at"
          ]
        ]
      ],
      [
        "screenID"=> "8",
        "ScreenName"=> "periodPredictionScreen",
        "keyword_data"=> [
          [
            "screenId"=> "8",
            "keyword_id"=> 95,
            "keyword_name"=> "cycleLength",
            "keyword_value"=> "Cycle Length"
          ],
          [
            "screenId"=> "8",
            "keyword_id"=> 96,
            "keyword_name"=> "days",
            "keyword_value"=> "days"
          ],
          [
            "screenId"=> "8",
            "keyword_id"=> 98,
            "keyword_name"=> "periodLengthText",
            "keyword_value"=> "This app makes prediction based on the cycle and period length settings. However, if you regularly log your period in the app, predictions would be based on logged data"
          ]
        ]
      ],
      [
        "screenID"=> "9",
        "ScreenName"=> "pregnancyScreen",
        "keyword_data"=> [
          [
            "screenId"=> "9",
            "keyword_id"=> 99,
            "keyword_name"=> "startDate",
            "keyword_value"=> "Start Date"
          ],
          [
            "screenId"=> "9",
            "keyword_id"=> 537,
            "keyword_name"=> "endDate",
            "keyword_value"=> "End Date"
          ],
          [
            "screenId"=> "9",
            "keyword_id"=> 100,
            "keyword_name"=> "startDateText",
            "keyword_value"=> "We start calculating your pregnancy due date from the first day of your last period to change the pregnancy start date, please click here and change the dates of the menstrual cycle"
          ],
          [
            "screenId"=> "9",
            "keyword_id"=> 101,
            "keyword_name"=> "dueData",
            "keyword_value"=> "Due Date"
          ],
          [
            "screenId"=> "9",
            "keyword_id"=> 102,
            "keyword_name"=> "startDateText",
            "keyword_value"=> "Tap to change your due date"
          ]
        ]
      ],
      [
        "screenID"=> "10",
        "ScreenName"=> "cycleRemindersScreen",
        "keyword_data"=> [
          [
            "screenId"=> "10",
            "keyword_id"=> 103,
            "keyword_name"=> "periodReminders",
            "keyword_value"=> "Period Reminders"
          ],
          [
            "screenId"=> "10",
            "keyword_id"=> 104,
            "keyword_name"=> "periodRemindersText",
            "keyword_value"=> "Remind me at the beginning and the end of period"
          ],
          [
            "screenId"=> "10",
            "keyword_id"=> 105,
            "keyword_name"=> "fertilityReminder",
            "keyword_value"=> "Fertility Reminder"
          ],
          [
            "screenId"=> "10",
            "keyword_id"=> 106,
            "keyword_name"=> "fertilityReminderText",
            "keyword_value"=> "Reminder that is triggered 1 day before you become fertile"
          ],
          [
            "screenId"=> "10",
            "keyword_id"=> 107,
            "keyword_name"=> "ovulationReminder",
            "keyword_value"=> "Ovulation Reminder"
          ],
          [
            "screenId"=> "10",
            "keyword_id"=> 108,
            "keyword_name"=> "ovulationReminderText",
            "keyword_value"=> "Reminder that is triggered 1 day before you ovulate"
          ]
        ]
      ],
      [
        "screenID"=> "11",
        "ScreenName"=> "medicineReminderScreen",
        "keyword_data"=> [
          [
            "screenId"=> "11",
            "keyword_id"=> 109,
            "keyword_name"=> "medicineReminders",
            "keyword_value"=> "Medicine reminders"
          ],
          [
            "screenId"=> "11",
            "keyword_id"=> 110,
            "keyword_name"=> "medicineRemindersText",
            "keyword_value"=> "Remind me to take my medicine on time"
          ]
        ]
      ],
      [
        "screenID"=> "12",
        "ScreenName"=> "meditationReminderScreen",
        "keyword_data"=> [
          [
            "screenId"=> "12",
            "keyword_id"=> 111,
            "keyword_name"=> "meditationReminders",
            "keyword_value"=> "Meditation reminders"
          ],
          [
            "screenId"=> "12",
            "keyword_id"=> 112,
            "keyword_name"=> "meditationRemindersText",
            "keyword_value"=> "Remind me to practice conscious breathing everyday"
          ]
        ]
      ],
      [
        "screenID"=> "13",
        "ScreenName"=> "dailyLoggingScreen",
        "keyword_data"=> [
          [
            "screenId"=> "13",
            "keyword_id"=> 113,
            "keyword_name"=> "dailyLoggingReminders",
            "keyword_value"=> "Daily logging reminders"
          ],
          [
            "screenId"=> "13",
            "keyword_id"=> 114,
            "keyword_name"=> "dailyLoggingRemindersText",
            "keyword_value"=> "Remind me to record important personal information in the app every day"
          ]
        ]
      ],
      [
        "screenID"=> "14",
        "ScreenName"=> "trackingScreen",
        "keyword_data"=> [
          [
            "screenId"=> "14",
            "keyword_id"=> 115,
            "keyword_name"=> "trackingReminders",
            "keyword_value"=> "Tracking reminders"
          ],
          [
            "screenId"=> "14",
            "keyword_id"=> 116,
            "keyword_name"=> "trackingRemindersText",
            "keyword_value"=> "Once a week remind me to record my well-being details in the app if I haven't logged anything for a while"
          ]
        ]
      ],
      [
        "screenID"=> "15",
        "ScreenName"=> "secretReminderScreen",
        "keyword_data"=> [
          [
            "screenId"=> "15",
            "keyword_id"=> 117,
            "keyword_name"=> "secretReminders",
            "keyword_value"=> "Secret Reminders"
          ],
          [
            "screenId"=> "15",
            "keyword_id"=> 118,
            "keyword_name"=> "chooseTheAppearance",
            "keyword_value"=> "Choose the appearance of your reminders"
          ],
          [
            "screenId"=> "15",
            "keyword_id"=> 119,
            "keyword_name"=> "hiRxr",
            "keyword_value"=> "Hi Rxr"
          ],
          [
            "screenId"=> "15",
            "keyword_id"=> 120,
            "keyword_name"=> "myCalender",
            "keyword_value"=> "My Calender"
          ],
          [
            "screenId"=> "15",
            "keyword_id"=> 121,
            "keyword_name"=> "hiRxrText",
            "keyword_value"=> "Your period is due on friday"
          ],
          [
            "screenId"=> "15",
            "keyword_id"=> 122,
            "keyword_name"=> "myCalenderText",
            "keyword_value"=> "Upcoming event. Tap to view"
          ]
        ]
      ],
      [
        "screenID"=> "16",
        "ScreenName"=> "yourNamePopUpScreen",
        "keyword_data"=> [
          [
            "screenId"=> "16",
            "keyword_id"=> 123,
            "keyword_name"=> "tellUsWhatToCallYou",
            "keyword_value"=> "Tell us what to call you"
          ]
        ]
      ],
      [
        "screenID"=> "17",
        "ScreenName"=> "secureAccessPinScreen",
        "keyword_data"=> [
          [
            "screenId"=> "17",
            "keyword_id"=> 124,
            "keyword_name"=> "pin",
            "keyword_value"=> "Pin"
          ],
          [
            "screenId"=> "17",
            "keyword_id"=> 125,
            "keyword_name"=> "chooseAPin",
            "keyword_value"=> "Choose A Pin"
          ],
          [
            "screenId"=> "17",
            "keyword_id"=> 126,
            "keyword_name"=> "fingerprintOrFaceRecognition",
            "keyword_value"=> "Fingerprint or Face Recognition"
          ],
          [
            "screenId"=> "17",
            "keyword_id"=> 127,
            "keyword_name"=> "ifTheDeviceSupportThisFeature",
            "keyword_value"=> "If the device supports this feature"
          ]
        ]
      ],
      [
        "screenID"=> "18",
        "ScreenName"=> "backUpDataScreen",
        "keyword_data"=> [
          [
            "screenId"=> "18",
            "keyword_id"=> 128,
            "keyword_name"=> "autoBackup",
            "keyword_value"=> "Auto Backup"
          ],
          [
            "screenId"=> "18",
            "keyword_id"=> 129,
            "keyword_name"=> "automaticBackupText",
            "keyword_value"=> "Automatic data backup is performed every 5 days"
          ],
          [
            "screenId"=> "18",
            "keyword_id"=> 130,
            "keyword_name"=> "automaticBackupDisclaimer",
            "keyword_value"=> "We don't make use of your data and don't sell it to third parties"
          ]
        ]
      ],
      [
        "screenID"=> "19",
        "ScreenName"=> "restoreDataScreen",
        "keyword_data"=> [
          [
            "screenId"=> "19",
            "keyword_id"=> 131,
            "keyword_name"=> "restoreDataDisclaimer",
            "keyword_value"=> "When you restore, the app data on your device is merged with the last backed up data"
          ]
        ]
      ],
      [
        "screenID"=> "20",
        "ScreenName"=> "deleteDataScreen",
        "keyword_data"=> [
          [
            "screenId"=> "20",
            "keyword_id"=> 132,
            "keyword_name"=> "deleteDataFromPhone",
            "keyword_value"=> "Delete data from phone"
          ],
          [
            "screenId"=> "20",
            "keyword_id"=> 133,
            "keyword_name"=> "deleteDataFromPhoneText",
            "keyword_value"=> "You can restore data from a backup if you have one"
          ]
        ]
      ],
      [
        "screenID"=> "21",
        "ScreenName"=> "calculatorToolsScreen",
        "keyword_data"=> [
          [
            "screenId"=> "21",
            "keyword_id"=> 134,
            "keyword_name"=> "calculator",
            "keyword_value"=> "Calculator"
          ],
          [
            "screenId"=> "21",
            "keyword_id"=> 135,
            "keyword_name"=> "pregnancyDueDateCalculator",
            "keyword_value"=> "Pregnancy due date calculator"
          ],
          [
            "screenId"=> "21",
            "keyword_id"=> 136,
            "keyword_name"=> "implantationCalculator",
            "keyword_value"=> "Implantation Calculator"
          ],
          [
            "screenId"=> "21",
            "keyword_id"=> 137,
            "keyword_name"=> "periodCalculator",
            "keyword_value"=> "Period Calculator"
          ],
          [
            "screenId"=> "21",
            "keyword_id"=> 138,
            "keyword_name"=> "pregnancyTestCalculator",
            "keyword_value"=> "Pregnancy test calculator"
          ],
          [
            "screenId"=> "21",
            "keyword_id"=> 139,
            "keyword_name"=> "ovulationCalculator",
            "keyword_value"=> "Ovulation calculator"
          ]
        ]
      ],
      [
        "screenID"=> "22",
        "ScreenName"=> "calculatorCommonScreen",
        "keyword_data"=> [
          [
            "screenId"=> "22",
            "keyword_id"=> 140,
            "keyword_name"=> "selectTheFirstDayOfYourPeriod",
            "keyword_value"=> "Select the first day of your last period"
          ],
          [
            "screenId"=> "22",
            "keyword_id"=> 141,
            "keyword_name"=> "calculateDueDate",
            "keyword_value"=> "Calculate due date"
          ],
          [
            "screenId"=> "22",
            "keyword_id"=> 142,
            "keyword_name"=> "calculatorDisclaimerText",
            "keyword_value"=> "We don't collect, process, or store any of the data that you enter while using this tool. All calculation are done exclusively in your locally, and we don't have access to the results. All data will be permanently erased after leaving or close the screen"
          ]
        ]
      ],
      [
        "screenID"=> "23",
        "ScreenName"=> "bookAppointmentScreen",
        "keyword_data"=> [
          [
            "screenId"=> "23",
            "keyword_id"=> 143,
            "keyword_name"=> "healthExperts",
            "keyword_value"=> "Health Experts"
          ],
          [
            "screenId"=> "23",
            "keyword_id"=> 362,
            "keyword_name"=> "myAppointments",
            "keyword_value"=> "My Appointments"
          ],
          [
            "screenId"=> "23",
            "keyword_id"=> 456,
            "keyword_name"=> "Back",
            "keyword_value"=> "Back"
          ],
          [
            "screenId"=> "23",
            "keyword_id"=> 457,
            "keyword_name"=> "areYouSureYouWantToCancelAppointment",
            "keyword_value"=> "Are You Sure you want to cancel Appointment ?"
          ],
          [
            "screenId"=> "23",
            "keyword_id"=> 458,
            "keyword_name"=> "editAppointmentClicked",
            "keyword_value"=> "Edit appointment clicked"
          ]
        ]
      ],
      [
        "screenID"=> "24",
        "ScreenName"=> "helpAndSupportScreen",
        "keyword_data"=> [
          [
            "screenId"=> "24",
            "keyword_id"=> 144,
            "keyword_name"=> "privacyAndPolicy",
            "keyword_value"=> "Privacy & Policy"
          ],
          [
            "screenId"=> "24",
            "keyword_id"=> 145,
            "keyword_name"=> "termsAndConditions",
            "keyword_value"=> "Terms & Conditions"
          ]
        ]
      ],
      [
        "screenID"=> "25",
        "ScreenName"=> "otherTextScreen",
        "keyword_data"=> [
          [
            "screenId"=> "25",
            "keyword_id"=> 146,
            "keyword_name"=> "cancel",
            "keyword_value"=> "Cancel"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 147,
            "keyword_name"=> "save",
            "keyword_value"=> "Save"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 148,
            "keyword_name"=> "restore",
            "keyword_value"=> "Restore"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 149,
            "keyword_name"=> "forgot",
            "keyword_value"=> "Forgot"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 150,
            "keyword_name"=> "tapHere",
            "keyword_value"=> "Tap here"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 151,
            "keyword_name"=> "areYouSure",
            "keyword_value"=> "Are you sure"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 181,
            "keyword_name"=> "yes",
            "keyword_value"=> "Yes"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 182,
            "keyword_name"=> "no",
            "keyword_value"=> "No"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 183,
            "keyword_name"=> "hey",
            "keyword_value"=> "Hey"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 184,
            "keyword_name"=> "today",
            "keyword_value"=> "Today"
          ],
          [
            "screenId"=> "25",
            "keyword_id"=> 185,
            "keyword_name"=> "tomorrow",
            "keyword_value"=> "Tomorrow"
          ]
        ]
      ],
      [
        "screenID"=> "26",
        "ScreenName"=> "profileScreen",
        "keyword_data"=> [
          [
            "screenId"=> "26",
            "keyword_id"=> 156,
            "keyword_name"=> "firstName",
            "keyword_value"=> "First Name"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 157,
            "keyword_name"=> "lastName",
            "keyword_value"=> "Last Name"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 158,
            "keyword_name"=> "email",
            "keyword_value"=> "Email"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 159,
            "keyword_name"=> "userType",
            "keyword_value"=> "User Type"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 160,
            "keyword_name"=> "password",
            "keyword_value"=> "Password"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 161,
            "keyword_name"=> "signUp",
            "keyword_value"=> "Sign Up"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 162,
            "keyword_name"=> "loginWithGoogle",
            "keyword_value"=> "Login with Google"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 163,
            "keyword_name"=> "login",
            "keyword_value"=> "Login"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 164,
            "keyword_name"=> "loginText",
            "keyword_value"=> "Welcome back, Login your account"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 491,
            "keyword_name"=> "anonymousUsersCannotChangeTheProfileImage",
            "keyword_value"=> "Anonymous users cannot change the profile image"
          ],
          [
            "screenId"=> "26",
            "keyword_id"=> 492,
            "keyword_name"=> "chooseImage",
            "keyword_value"=> "Choose Image"
          ]
        ]
      ],
      [
        "screenID"=> "27",
        "ScreenName"=> "userNavigationTab",
        "keyword_data"=> [
          [
            "screenId"=> "27",
            "keyword_id"=> 165,
            "keyword_name"=> "analysis",
            "keyword_value"=> "Analysis"
          ],
          [
            "screenId"=> "27",
            "keyword_id"=> 166,
            "keyword_name"=> "selfCare",
            "keyword_value"=> "Self Care"
          ],
          [
            "screenId"=> "27",
            "keyword_id"=> 167,
            "keyword_name"=> "reports",
            "keyword_value"=> "Reports"
          ]
        ]
      ],
      [
        "screenID"=> "28",
        "ScreenName"=> "educationalScreen",
        "keyword_data"=> [
          [
            "screenId"=> "28",
            "keyword_id"=> 168,
            "keyword_name"=> "doYouWantToJoinSession",
            "keyword_value"=> "Do you want to join session"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 169,
            "keyword_name"=> "joinSession",
            "keyword_value"=> "Join Session"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 170,
            "keyword_name"=> "yesJoinNow",
            "keyword_value"=> "Yes, Join Now"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 171,
            "keyword_name"=> "noIDont",
            "keyword_value"=> "No, I don't"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 448,
            "keyword_name"=> "viewProfile",
            "keyword_value"=> "View Profile"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 541,
            "keyword_name"=> "SetAppointmentDays",
            "keyword_value"=> "Set Appointment Days"
          ]
        ]
      ],
      [
        "screenID"=> "29",
        "ScreenName"=> "doctorAppointmentScreen",
        "keyword_data"=> [
          [
            "screenId"=> "28",
            "keyword_id"=> 174,
            "keyword_name"=> "all",
            "keyword_value"=> "All appointment"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 175,
            "keyword_name"=> "upcomingAppointments",
            "keyword_value"=> "Upcoming appointment"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 176,
            "keyword_name"=> "pastAppointments",
            "keyword_value"=> "Past appointment"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 177,
            "keyword_name"=> "appointmentAcceptConfirm",
            "keyword_value"=> "Accept Appointment"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 178,
            "keyword_name"=> "appointmentRejectConfirm",
            "keyword_value"=> "Reject Appointment"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 179,
            "keyword_name"=> "appointments",
            "keyword_value"=> "Appointments"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 180,
            "keyword_name"=> "rejected",
            "keyword_value"=> "Rejected"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 445,
            "keyword_name"=> "cancelAppointment",
            "keyword_value"=> "Cancel Appointment"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 446,
            "keyword_name"=> "reasonForScheduleChange",
            "keyword_value"=> "Reason For Schedule Change"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 447,
            "keyword_name"=> "Management",
            "keyword_value"=> "Management"
          ],
          [
            "screenId"=> "28",
            "keyword_id"=> 180,
            "keyword_name"=> "rejected",
            "keyword_value"=> "Rejected"
          ]
        ]
      ],
      [
        "screenID"=> "31",
        "ScreenName"=> "addSessionScreen",
        "keyword_data"=> [
          [
            "screenId"=> "31",
            "keyword_id"=> 186,
            "keyword_name"=> "addSession",
            "keyword_value"=> "Add session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 187,
            "keyword_name"=> "editSession",
            "keyword_value"=> "Edit session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 188,
            "keyword_name"=> "weekDays",
            "keyword_value"=> "Week days"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 189,
            "keyword_name"=> "morningSession",
            "keyword_value"=> "Morning Session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 190,
            "keyword_name"=> "startTime",
            "keyword_value"=> "Start time"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 191,
            "keyword_name"=> "endTime",
            "keyword_value"=> "End time"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 192,
            "keyword_name"=> "EveningSession",
            "keyword_value"=> "Evening Session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 193,
            "keyword_name"=> "Expired",
            "keyword_value"=> "Expired"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 194,
            "keyword_name"=> "Accepted",
            "keyword_value"=> "Accepted"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 515,
            "keyword_name"=> "Completed",
            "keyword_value"=> "Completed"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 516,
            "keyword_name"=> "Pending",
            "keyword_value"=> "Pending"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 517,
            "keyword_name"=> "Reject",
            "keyword_value"=> "Reject"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 518,
            "keyword_name"=> "Accept",
            "keyword_value"=> "Accept"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 203,
            "keyword_name"=> "todayAppointment",
            "keyword_value"=> "Today appointment"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 204,
            "keyword_name"=> "tomorrowAppointments",
            "keyword_value"=> "Tomorrow appointments"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 205,
            "keyword_name"=> "todayEducationSession",
            "keyword_value"=> "Today education session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 206,
            "keyword_name"=> "tomorrowEducationSession",
            "keyword_value"=> "Tomorrow education session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 207,
            "keyword_name"=> "newQuestions",
            "keyword_value"=> "New questions"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 208,
            "keyword_name"=> "myAnswers",
            "keyword_value"=> "My answers"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 209,
            "keyword_name"=> "pendingQuestions",
            "keyword_value"=> "Pending Questions"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 210,
            "keyword_name"=> "seeAll",
            "keyword_value"=> "See all"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 211,
            "keyword_name"=> "typeYourAnswer",
            "keyword_value"=> "Type your answer here"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 212,
            "keyword_name"=> "submitAnswer",
            "keyword_value"=> "Submit Answer"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 213,
            "keyword_name"=> "answerQuestion",
            "keyword_value"=> "Answer Question"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 214,
            "keyword_name"=> "yourUpComingHolidays",
            "keyword_value"=> "Your upcoming holidays"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 215,
            "keyword_name"=> "upcomingEducationSession",
            "keyword_value"=> "Upcoming Education Session"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 284,
            "keyword_name"=> "morning",
            "keyword_value"=> "Morning"
          ],
          [
            "screenId"=> "31",
            "keyword_id"=> 285,
            "keyword_name"=> "evening",
            "keyword_value"=> "Evening"
          ]



        ]
      ],
      [
        "screenID"=> "32",
        "ScreenName"=> "ChangePasswordScreen",
        "keyword_data"=> [
          [
            "screenId"=> "32",
            "keyword_id"=> 195,
            "keyword_name"=> "changePassword",
            "keyword_value"=> "Change Password"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 196,
            "keyword_name"=> "oldPassword",
            "keyword_value"=> "Old Password"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 197,
            "keyword_name"=> "pleaseEnterPassword",
            "keyword_value"=> "Please enter password"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 198,
            "keyword_name"=> "minimumlength",
            "keyword_value"=> "Minimum length is 6"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 199,
            "keyword_name"=> "newPassword",
            "keyword_value"=> "New Password"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 200,
            "keyword_name"=> "confirmPassword",
            "keyword_value"=> "Confirm Password"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 201,
            "keyword_name"=> "PleaseConfirmYourPassword",
            "keyword_value"=> "Please confirm your password"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 202,
            "keyword_name"=> "passwordDoNotMatch",
            "keyword_value"=> "Passwords do not match"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 335,
            "keyword_name"=> "emailFieldIsRequired",
            "keyword_value"=> "Email field is required"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 336,
            "keyword_name"=> "EmailIsNotValid",
            "keyword_value"=> "Email is not valid"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 337,
            "keyword_name"=> "submit",
            "keyword_value"=> "Submit"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 459,
            "keyword_name"=> "confirmThisPassword",
            "keyword_value"=> "Confirm this password?"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 460,
            "keyword_name"=> "pinSavedSuccessfully",
            "keyword_value"=> "Pin saved successfully!"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 461,
            "keyword_name"=> "setPin",
            "keyword_value"=> "Set Pin"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 462,
            "keyword_name"=> "reenterYour4digitPIN",
            "keyword_value"=> "Re-enter your 4-digit PIN"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 463,
            "keyword_name"=> "enterDigitPIN",
            "keyword_value"=> "Enter a 4-digit PIN"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 464,
            "keyword_name"=> "reset",
            "keyword_value"=> "Reset"
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 465,
            "keyword_name"=> "passwordsDoNotMatch",
            "keyword_value"=> "Passwords don't match. Please re-enter."
          ],
          [
            "screenId"=> "32",
            "keyword_id"=> 466,
            "keyword_name"=> "pinDoesNotMatch",
            "keyword_value"=> "Pin does not match"
          ]
        ]
      ],
      [
        "screenID"=> "33",
        "ScreenName"=> "DoctorBlogScreen",
        "keyword_data"=> [
          [
            "screenId"=> "33",
            "keyword_id"=> 216,
            "keyword_name"=> "yourBlog",
            "keyword_value"=> "Your Blog"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 217,
            "keyword_name"=> "blog",
            "keyword_value"=> "Blog"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 218,
            "keyword_name"=> "editBlog",
            "keyword_value"=> "Do you want to edit this blog?"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 219,
            "keyword_name"=> "edit",
            "keyword_value"=> "Edit"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 220,
            "keyword_name"=> "deleteThisBlog",
            "keyword_value"=> "Are you sure you want to delete this blog?"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 221,
            "keyword_name"=> "delete",
            "keyword_value"=> "Delete"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 222,
            "keyword_name"=> "publishedDate",
            "keyword_value"=> "Published Date"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 519,
            "keyword_name"=> "SearchBlog",
            "keyword_value"=> "Search Blog"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 520,
            "keyword_name"=> "SwipeLeftToEdit",
            "keyword_value"=> "Swipe left to edit"
          ],
          [
            "screenId"=> "33",
            "keyword_id"=> 521,
            "keyword_name"=> "SwipeRightToDelete",
            "keyword_value"=> "Swipe right to delete"
          ]
        ]
      ],
      [
        "screenID"=> "34",
        "ScreenName"=> "DoctorLoginScreen",
        "keyword_data"=> [
          [
            "screenId"=> "34",
            "keyword_id"=> 223,
            "keyword_name"=> "pleaseEnterEmail",
            "keyword_value"=> "please enter email"
          ],
          [
            "screenId"=> "34",
            "keyword_id"=> 224,
            "keyword_name"=> "pleaseEnterValidEmail",
            "keyword_value"=> "please enter valid email"
          ],
          [
            "screenId"=> "34",
            "keyword_id"=> 225,
            "keyword_name"=> "forgotPassword",
            "keyword_value"=> "Forgot password"
          ],
          [
            "screenId"=> "34",
            "keyword_id"=> 225,
            "keyword_name"=> "forgotPassword",
            "keyword_value"=> "Forgot password"
          ],
          [
            "screenId"=> "34",
            "keyword_id"=> 351,
            "keyword_name"=> "'welcomeBackLoginYourAccount",
            "keyword_value"=> "'Welcome back, Login your account"
          ]
        ]
      ],
      [
        "screenID"=> "35",
        "ScreenName"=> "DoctorProfileOverviewScreen",
        "keyword_data"=> [
          [
            "screenId"=> "35",
            "keyword_id"=> 226,
            "keyword_name"=> "doctorProfile",
            "keyword_value"=> "Doctor Profile"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 227,
            "keyword_name"=> "shortDescription",
            "keyword_value"=> "Short Description"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 228,
            "keyword_name"=> "career",
            "keyword_value"=> "Career"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 229,
            "keyword_name"=> "education",
            "keyword_value"=> "Education"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 230,
            "keyword_name"=> "awardsAndAchievements",
            "keyword_value"=> "Awards and Achievements"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 231,
            "keyword_name"=> "AreasOfExpertise",
            "keyword_value"=> "Areas of Expertise"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 522,
            "keyword_name"=> "reviews",
            "keyword_value"=> "Reviews"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 523,
            "keyword_name"=> "NoAvailableDays",
            "keyword_value"=> "No available days"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 524,
            "keyword_name"=> "NoAvailableTimeRange",
            "keyword_value"=> "No available time range"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 525,
            "keyword_name"=> "Availability",
            "keyword_value"=> "Availability"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 526,
            "keyword_name"=> "AllReviews",
            "keyword_value"=> "All Reviews"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 527,
            "keyword_name"=> "NoReviewsAvailable",
            "keyword_value"=> "No reviews available"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 528,
            "keyword_name"=> "NoRatings",
            "keyword_value"=> "No ratings"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 556,
            "keyword_name"=> "yearsOfExperience",
            "keyword_value"=> "years of Experience"
          ],
          [
            "screenId"=> "35",
            "keyword_id"=> 557,
            "keyword_name"=> "SearchExperts",
            "keyword_value"=> "Search Experts"
          ]
        ]
      ],
      [
        "screenID"=> "36",
        "ScreenName"=> "DoctorProfileScreen",
        "keyword_data"=> [
          [
            "screenId"=> "36",
            "keyword_id"=> 232,
            "keyword_name"=> "myHolidayList",
            "keyword_value"=> "My Holiday List"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 233,
            "keyword_name"=> "setAppointmentTime",
            "keyword_value"=> "Set Appointment time"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 234,
            "keyword_name"=> "myQuestion",
            "keyword_value"=> "My Question"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 235,
            "keyword_name"=> "appSettings",
            "keyword_value"=> "App Settings"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 236,
            "keyword_name"=> "areYouSureLogout",
            "keyword_value"=> "Are you sure log out?"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 237,
            "keyword_name"=> "generalSettings",
            "keyword_value"=> "General Settings"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 238,
            "keyword_name"=> "profile",
            "keyword_value"=> "Profile"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 542,
            "keyword_name"=> "MyBlogs",
            "keyword_value"=> "My Blogs"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 543,
            "keyword_name"=> "NameIsRequired",
            "keyword_value"=> "Name is required"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 544,
            "keyword_name"=> "AtLeastOneTagIsRequired",
            "keyword_value"=> "Name is required"
          ],
          [
            "screenId"=> "36",
            "keyword_id"=> 545,
            "keyword_name"=> "GoalTypeIsRequired",
            "keyword_value"=> "Goal type is required"
          ]
        ]
      ],
      [
        "screenID"=> "37",
        "ScreenName"=> "EditBlogScreen",
        "keyword_data"=> [
          [
            "screenId"=> "37",
            "keyword_id"=> 239,
            "keyword_name"=> "createBlog",
            "keyword_value"=> "Create Blog"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 240,
            "keyword_name"=> "blogEdit",
            "keyword_value"=> "Edit Blog"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 241,
            "keyword_name"=> "blogName",
            "keyword_value"=> "Blog Name"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 242,
            "keyword_name"=> "addNameYourBlog",
            "keyword_value"=> "Add name of your blog"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 243,
            "keyword_name"=> "blogTags",
            "keyword_value"=> "Blog Tags"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 244,
            "keyword_name"=> "goalType",
            "keyword_value"=> "Goal Type"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 245,
            "keyword_name"=> "blogImage",
            "keyword_value"=> "Blog Image"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 246,
            "keyword_name"=> "addImage",
            "keyword_value"=> "Add image"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 247,
            "keyword_name"=> "references",
            "keyword_value"=> "References"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 248,
            "keyword_name"=> "addReferences",
            "keyword_value"=> "Add references"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 249,
            "keyword_name"=> "blogDescription",
            "keyword_value"=> "Blog Description"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 323,
            "keyword_name"=> "reviewedBy",
            "keyword_value"=> "Reviewed By"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 324,
            "keyword_name"=> "thisArticleWasPublishedOn",
            "keyword_value"=> "This article was published on"
          ],
          [
            "screenId"=> "37",
            "keyword_id"=> 325,
            "keyword_name"=> "Bookmarks",
            "keyword_value"=> "Bookmarks"
          ]
        ]
      ],
      [
        "screenID"=> "38",
        "ScreenName"=> "EditEducationSessionScreen",
        "keyword_data"=> [
          [
            "screenId"=> "38",
            "keyword_id"=> 250,
            "keyword_name"=> "editEducationSession",
            "keyword_value"=> "Edit Education Session"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 251,
            "keyword_name"=> "addEducationSession",
            "keyword_value"=> "Add Education Session"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 252,
            "keyword_name"=> "title",
            "keyword_value"=> "Title"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 253,
            "keyword_name"=> "addTitleOfYourSession",
            "keyword_value"=> "Add title of your session"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 254,
            "keyword_name"=> "dateAndTime",
            "keyword_value"=> "Date and Time"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 255,
            "keyword_name"=> "duration",
            "keyword_value"=> "Duration"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 256,
            "keyword_name"=> "price",
            "keyword_value"=> "Price"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 257,
            "keyword_name"=> "addPriceOfYourSession",
            "keyword_value"=> "Add price of your session"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 258,
            "keyword_name"=> "isTheSessionOffline",
            "keyword_value"=> "Is the session offline?"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 259,
            "keyword_name"=> "addVenueOfYourSession",
            "keyword_value"=> "Add venue of your session"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 260,
            "keyword_name"=> "address",
            "keyword_value"=> "Address"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 261,
            "keyword_name"=> "sessionLink",
            "keyword_value"=> "Session Link"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 262,
            "keyword_name"=> "addLinkHere",
            "keyword_value"=> "Add Link here"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 263,
            "keyword_name"=> "description",
            "keyword_value"=> "Description"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 264,
            "keyword_name"=> "addDescription",
            "keyword_value"=> "Add Description"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 278,
            "keyword_name"=> "educationSession",
            "keyword_value"=> "Education Session"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 279,
            "keyword_name"=> "Note",
            "keyword_value"=> "Tap to Edit"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 280,
            "keyword_name"=> "deleteEducationSession",
            "keyword_value"=> "Are you sure you want to delete this session?"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 326,
            "keyword_name"=> "Paid",
            "keyword_value"=> "Paid"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 327,
            "keyword_name"=> "free",
            "keyword_value"=> "Free"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 328,
            "keyword_name"=> "pleaseEnterLastName",
            "keyword_value"=> "Please Enter Last Name"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 329,
            "keyword_name"=> "pleaseEnterFirstName",
            "keyword_value"=> "Please Enter First Name"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 330,
            "keyword_name"=> "NoSessionAvailable",
            "keyword_value"=> "No Session Available"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 331,
            "keyword_name"=> "sessionEnrolled",
            "keyword_value"=> "Session Enrolled"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 529,
            "keyword_name"=> "TitleIsRequired",
            "keyword_value"=> "Title is required"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 530,
            "keyword_name"=> "DateAndTimeNotSelected",
            "keyword_value"=> "Date and time not selected"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 531,
            "keyword_name"=> "DateAndTimeNotSelected",
            "keyword_value"=> "Date and time not selected"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 532,
            "keyword_name"=> "durationMustBeGreaterThan0Minutes",
            "keyword_value"=> "Duration must be greater than 0 minutes"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 533,
            "keyword_name"=> "minutes",
            "keyword_value"=> "minutes"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 534,
            "keyword_name"=> "hour",
            "keyword_value"=> "hour"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 558,
            "keyword_name"=> "addressIsRequired",
            "keyword_value"=> "Address is required"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 559,
            "keyword_name"=> "LinkIsRequired",
            "keyword_value"=> "Link is required"
          ],
          [
            "screenId"=> "38",
            "keyword_id"=> 560,
            "keyword_name"=> "InvalidLinkFormat",
            "keyword_value"=> "Invalid Link Format"
          ]

        ]
      ],
      [
        "screenID"=> "39",
        "ScreenName"=> "EditHolidayScreen",
        "keyword_data"=> [
          [
            "screenId"=> "39",
            "keyword_id"=> 265,
            "keyword_name"=> "addHoliday",
            "keyword_value"=> "Add Holiday"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 266,
            "keyword_name"=> "editHoliday",
            "keyword_value"=> "Edit Holiday"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 267,
            "keyword_name"=> "enterYourReasonForHoliday",
            "keyword_value"=> "Enter your reason for holiday"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 268,
            "keyword_name"=> "selectDateRange",
            "keyword_value"=> "Select Date Range"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 269,
            "keyword_name"=> "leaveFor",
            "keyword_value"=> "Leave for"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 281,
            "keyword_name"=> "deleteHoliday",
            "keyword_value"=> "Are you sure you want to delete this holiday?"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 535,
            "keyword_name"=> "SelectRange",
            "keyword_value"=> "Select Range"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 536,
            "keyword_name"=> "PleaseSelectADateRange",
            "keyword_value"=> "Please select a date range"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 538,
            "keyword_name"=> "Reason",
            "keyword_value"=> "Reason"
          ],
          [
            "screenId"=> "39",
            "keyword_id"=> 539,
            "keyword_name"=> "TypeYourBlogDescriptionHere",
            "keyword_value"=> "Type Your Blog Description Here..."
          ]
        ]
      ],
      [
        "screenID"=> "40",
        "ScreenName"=> "EditHolidayScreen",
        "keyword_data"=> [
          [
            "screenId"=> "40",
            "keyword_id"=> 270,
            "keyword_name"=> "editProfile",
            "keyword_value"=> "Edit Profile"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 271,
            "keyword_name"=> "name",
            "keyword_value"=> "Name"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 272,
            "keyword_name"=> "enterYourName",
            "keyword_value"=> "Enter your name"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 273,
            "keyword_name"=> "enterEmail",
            "keyword_value"=> "Enter email"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 274,
            "keyword_name"=> "tagLine",
            "keyword_value"=> "Tag Line"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 275,
            "keyword_name"=> "enterYourTagLine",
            "keyword_value"=> "Enter your tag line"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 276,
            "keyword_name"=> "camera",
            "keyword_value"=> "Camera"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 277,
            "keyword_name"=> "gallery",
            "keyword_value"=> "Gallery"
          ],
          [
            "screenId"=> "40",
            "keyword_id"=> 347,
            "keyword_name"=> "Podcast",
            "keyword_value"=> "Podcast"
          ]
        ]
      ],
      [
        "screenID"=> "41",
        "ScreenName"=> "PendingQuestionsScreen",
        "keyword_data"=> [
          [
            "screenId"=> "41",
            "keyword_id"=> 282,
            "keyword_name"=> "noPendingQuestions",
            "keyword_value"=> "There are no pending questions! Check back later"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 283,
            "keyword_name"=> "askedOn",
            "keyword_value"=> "Asked on"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 506,
            "keyword_name"=> "yourAnswer",
            "keyword_value"=> "Your Answer"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 507,
            "keyword_name"=> "readLess",
            "keyword_value"=> "Read Less"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 508,
            "keyword_name"=> "readMore",
            "keyword_value"=> "Read More"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 509,
            "keyword_name"=> "YourInternetIsNotWorking",
            "keyword_value"=> "Your Internet is not working"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 510,
            "keyword_name"=> "Done",
            "keyword_value"=> "Done"
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 511,
            "keyword_name"=> "PleaseSelectAtLeastOneWeekday",
            "keyword_value"=> "Please select at least one weekday."
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 512,
            "keyword_name"=> "PleaseSelectBothStartAndEndTimesForTheSession",
            "keyword_value"=> "Please select both start and end times for the session."
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 513,
            "keyword_name"=> "FailedToUpdateSession",
            "keyword_value"=> "Failed to update session."
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 514,
            "keyword_name"=> "startTimeMustBEarlierThanEndTime",
            "keyword_value"=> "start time must be earlier than end time."
          ],
          [
            "screenId"=> "41",
            "keyword_id"=> 540,
            "keyword_name"=> "NoAnsweredQuestions",
            "keyword_value"=> "No answered questions"
          ]
        ]
      ],
      [
        "screenID"=> "42",
        "ScreenName"=> "PleaseWaitScreen",
        "keyword_data"=> [
          [
            "screenId"=> "42",
            "keyword_id"=> 286,
            "keyword_name"=> "pleaseWait",
            "keyword_value"=> "Please Wait"
          ],
          [
            "screenId"=> "42",
            "keyword_id"=> 287,
            "keyword_name"=> "weAreBuilding",
            "keyword_value"=> "We are building your experience..."
          ]
        ]
      ],
      [
        "screenID"=> "43",
        "ScreenName"=> "ImplantationCalculatorScreen",
        "keyword_data"=> [
          [
            "screenId"=> "43",
            "keyword_id"=> 288,
            "keyword_name"=> "averageCycle",
            "keyword_value"=> "Average cycle"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 289,
            "keyword_name"=> "howLongIsYourAverageCycle",
            "keyword_value"=> "How long is your average cycle?"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 290,
            "keyword_name"=> "selectAverageCycleDays",
            "keyword_value"=> "Select average cycle days"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 291,
            "keyword_name"=> "calculateImplantation",
            "keyword_value"=> "Calculate implantation"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 292,
            "keyword_name"=> "implantationResult",
            "keyword_value"=> "Implantation result"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 293,
            "keyword_name"=> "recalculate",
            "keyword_value"=> "Recalculate"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 294,
            "keyword_name"=> "yourImplantationRangeIsBetween",
            "keyword_value"=> "Your implantation range is between"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 295,
            "keyword_name"=> "CalculateFertilityDays",
            "keyword_value"=> "Calculate fertility days"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 296,
            "keyword_name"=> "ovulationDay",
            "keyword_value"=> "Ovulation day"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 297,
            "keyword_name"=> "fertilityWindow",
            "keyword_value"=> "Fertility window"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 298,
            "keyword_name"=> "yourBestDaysToConceiveAre",
            "keyword_value"=> "Your best days to conceive are"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 299,
            "keyword_name"=> "ovulationResult",
            "keyword_value"=> "Ovulation result"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 300,
            "keyword_name"=> "lastDays",
            "keyword_value"=> "Last days"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 301,
            "keyword_name"=> "selectDate",
            "keyword_value"=> "Select date"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 302,
            "keyword_name"=> "howManyDaysDidItLast",
            "keyword_value"=> "How many days did it last?"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 303,
            "keyword_name"=> "yourEstimatedPeriodDatesAre",
            "keyword_value"=> "Your estimated period dates are"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 304,
            "keyword_name"=> "pregnancyDueDateResult",
            "keyword_value"=> "Pregnancy due date result"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 305,
            "keyword_name"=> "youWillMeetYourBabyOn",
            "keyword_value"=> "You will meet your baby on"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 306,
            "keyword_name"=> "calculatePregnancyDays",
            "keyword_value"=> "Calculate Pregnancy Days"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 307,
            "keyword_name"=> "PregnancyTestResult",
            "keyword_value"=> "Pregnancy test result"
          ],
          [
            "screenId"=> "43",
            "keyword_id"=> 308,
            "keyword_name"=> "firstDayYouCanTestIs",
            "keyword_value"=> "First day you can test is"
          ]
        ]
      ],
      [
        "screenID"=> "44",
        "ScreenName"=> "ShakingUpdateDialog",
        "keyword_data"=> [
          [
            "screenId"=> "44",
            "keyword_id"=> 309,
            "keyword_name"=> "timeToUpdate",
            "keyword_value"=> "Time to Update!"
          ],
          [
            "screenId"=> "44",
            "keyword_id"=> 310,
            "keyword_name"=> "'weMadeYourAppEvenBetter.",
            "keyword_value"=> "'We’ve made your app even better."
          ],
          [
            "screenId"=> "44",
            "keyword_id"=> 311,
            "keyword_name"=> "toContinueEnjoyingTheLatestFeaturesAndImprovements",
            "keyword_value"=> "To continue enjoying the latest features and improvements, please update to the newest version."
          ],
          [
            "screenId"=> "44",
            "keyword_id"=> 312,
            "keyword_name"=> "updateNow",
            "keyword_value"=> "Update Now"
          ],
          [
            "screenId"=> "44",
            "keyword_id"=> 313,
            "keyword_name"=> "noThankYou",
            "keyword_value"=> "No, thank you"
          ]
        ]
      ],
      [
        "screenID"=> "45",
        "ScreenName"=> "AskAnExpertScreen",
        "keyword_data"=> [
          [
            "screenId"=> "45",
            "keyword_id"=> 314,
            "keyword_name"=> "askAnExpert",
            "keyword_value"=> "Ask an expert"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 315,
            "keyword_name"=> "pleaseEnterTitle",
            "keyword_value"=> "Please Enter Title"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 316,
            "keyword_name"=> "questionTitle",
            "keyword_value"=> "Question Title"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 317,
            "keyword_name"=> "pleaseEnterQuestion",
            "keyword_value"=> "Please Enter Question"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 318,
            "keyword_name"=> "askYourQuestion",
            "keyword_value"=> "Ask Your Question"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 319,
            "keyword_name"=> "addAnImage",
            "keyword_value"=> "Add an image"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 320,
            "keyword_name"=> "getExpertAnswersToYourHealthQuestions",
            "keyword_value"=> "Get expert answers to your health questions."
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 321,
            "keyword_name"=> "myQuestions",
            "keyword_value"=> "My Questions"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 322,
            "keyword_name"=> "areYouSureYouWantToDeleteThisQuestion",
            "keyword_value"=> "Are you sure you want to delete this question?"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 443,
            "keyword_name"=> "deleteQuestion",
            "keyword_value"=> "Delete Question"
          ],
          [
            "screenId"=> "45",
            "keyword_id"=> 444,
            "keyword_name"=> "answeredOn",
            "keyword_value"=> "Answered On"
          ]
        ]
      ],
      [
        "screenID"=> "46",
        "ScreenName"=> "GraphsAndReportScreen",
        "keyword_data"=> [
          [
            "screenId"=> "46",
            "keyword_id"=> 338,
            "keyword_name"=> "viewImage",
            "keyword_value"=> "View Image"
          ],
          [
            "screenId"=> "46",
            "keyword_id"=> 339,
            "keyword_name"=> "viewPDF",
            "keyword_value"=> "View PDF"
          ],
          [
            "screenId"=> "46",
            "keyword_id"=> 340,
            "keyword_name"=> "noSataAvailableToGenerateYourGraph",
            "keyword_value"=> "No data available to generate your graph."
          ],
          [
            "screenId"=> "46",
            "keyword_id"=> 341,
            "keyword_name"=> "pleaseLogYourData",
            "keyword_value"=> "Please log your data"
          ],
          [
            "screenId"=> "46",
            "keyword_id"=> 342,
            "keyword_name"=> "toGenerateDetailedGraphs",
            "keyword_value"=> "to generate detailed graphs, unlock personalized insights, and track your cycle regularly"
          ],
          [
            "screenId"=> "46",
            "keyword_id"=> 343,
            "keyword_name"=> "bodyTemperature",
            "keyword_value"=> "Body Temperature"
          ],
          [
            "screenId"=> "46",
            "keyword_id"=> 344,
            "keyword_name"=> "sleep",
            "keyword_value"=> "Sleep"
          ]
        ]
      ],
      [
        "screenID"=> "47",
        "ScreenName"=> "LiveChatScreen",
        "keyword_data"=> [
          [
            "screenId"=> "47",
            "keyword_id"=> 345,
            "keyword_name"=> "resetChatSession",
            "keyword_value"=> "Reset Chat Session"
          ],
          [
            "screenId"=> "47",
            "keyword_id"=> 346,
            "keyword_name"=> "openCrispChat",
            "keyword_value"=> "Open Crisp Chat"
          ]
        ]
      ],
      [
        "screenID"=> "48",
        "ScreenName"=> "PregnancyDetailScreen",
        "keyword_data"=> [
          [
            "screenId"=> "48",
            "keyword_id"=> 348,
            "keyword_name"=> "noContentAvailable",
            "keyword_value"=> "No Content Available"
          ],
          [
            "screenId"=> "48",
            "keyword_id"=> 349,
            "keyword_name"=> "Unknown",
            "keyword_value"=> "Unknown"
          ],
          [
            "screenId"=> "48",
            "keyword_id"=> 350,
            "keyword_name"=> "privacyPolicy",
            "keyword_value"=> "Privacy policy"
          ],
          [
            "screenId"=> "48",
            "keyword_id"=> 467,
            "keyword_name"=> "Week",
            "keyword_value"=> "Week"
          ],
          [
            "screenId"=> "48",
            "keyword_id"=> 468,
            "keyword_name"=> "noTagline",
            "keyword_value"=> "No Tagline"
          ],
          [
            "screenId"=> "48",
            "keyword_id"=> 469,
            "keyword_name"=> "noTitle",
            "keyword_value"=> "No Title"
          ]
        ]
      ],
      [
        "screenID"=> "49",
        "ScreenName"=> "SignUpScreen",
        "keyword_data"=> [
          [
            "screenId"=> "49",
            "keyword_id"=> 352,
            "keyword_name"=> "continueWithGoogle",
            "keyword_value"=> "Continue with Google"
          ],
          [
            "screenId"=> "49",
            "keyword_id"=> 353,
            "keyword_name"=> "continueWithApple",
            "keyword_value"=> "Continue with Apple"
          ]
        ]
      ],
      [
        "screenID"=> "50",
        "ScreenName"=> "SubscriptionScreen",
        "keyword_data"=> [
          [
            "screenId"=> "50",
            "keyword_id"=> 354,
            "keyword_name"=> "congratulations",
            "keyword_value"=> "Congratulations!"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 355,
            "keyword_name"=> "youUnlockedOneYearProSubscription",
            "keyword_value"=> "You unlocked one year pro subscription"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 356,
            "keyword_name"=> "benefitsUnlocked",
            "keyword_value"=> "Benefits Unlocked=>"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 357,
            "keyword_name"=> "thisIsNotAnAutoRenewalSubscription",
            "keyword_value"=> "This is not an auto-renewal subscription. You must manually renew for Monthly/Yearly."
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 358,
            "keyword_name"=> "startExploringPremiumFeatures",
            "keyword_value"=> "Start exploring premium features"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 359,
            "keyword_name"=> "UserMustHaveToPayManuallyForMonthly",
            "keyword_value"=> "This is not auto renewal subscription. User must have to pay manually for Monthly/Yearly"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 360,
            "keyword_name"=> "yearlyPlan",
            "keyword_value"=> "Yearly Plan"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 361,
            "keyword_name"=> "monthly",
            "keyword_value"=> "Monthly"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 478,
            "keyword_name"=> "subscriptionPlans",
            "keyword_value"=> "Subscription Plans"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 479,
            "keyword_name"=> "subscribeNowToUnlockAllFeatures",
            "keyword_value"=> "Subscribe now to unlock all features!"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 480,
            "keyword_name"=> "viewPlans",
            "keyword_value"=> "View Plans"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 481,
            "keyword_name"=> "Retry",
            "keyword_value"=> "Retry"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 482,
            "keyword_name"=> "noSubscriptionHistoryAvailable",
            "keyword_value"=> "No subscription history available"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 483,
            "keyword_name"=> "Expires",
            "keyword_value"=> "Expires"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 484,
            "keyword_name"=> "Features",
            "keyword_value"=> "Features"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 485,
            "keyword_name"=> "Active",
            "keyword_value"=> "Active"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 486,
            "keyword_name"=> "Inactive",
            "keyword_value"=> "Inactive"
          ],
          [
            "screenId"=> "50",
            "keyword_id"=> 487,
            "keyword_name"=> "History",
            "keyword_value"=> "History"
          ]
        ]
      ],
      [
        "screenID"=> "51",
        "ScreenName"=> "SettingScreen",
        "keyword_data"=> [
          [
            "screenId"=> "51",
            "keyword_id"=> 363,
            "keyword_name"=> "confirmation",
            "keyword_value"=> "Confirmation"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 364,
            "keyword_name"=> "areYouSureYouWantToSwitchYourGoalType",
            "keyword_value"=> "Are you sure you want to switch your goal type?"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 365,
            "keyword_name"=> "pleaseWaitWhileDataIsBeenAdded",
            "keyword_value"=> "Please wait while data is been added."
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 366,
            "keyword_name"=> "dataHasBeenAdded",
            "keyword_value"=> "Data has been added."
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 367,
            "keyword_name"=> "waterDrinkingReminders",
            "keyword_value"=> "Water Drinking Reminders"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 368,
            "keyword_name"=> "weightLoggingReminders",
            "keyword_value"=> "Weight Logging Reminders"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 369,
            "keyword_name"=> "sleepReminders",
            "keyword_value"=> "Sleep Reminders"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 370,
            "keyword_name"=> "bodyTemperatureReminders",
            "keyword_value"=> "Body temperature Reminders"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 371,
            "keyword_name"=> "joinAsAnAppUser",
            "keyword_value"=> "Join as an App User"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 372,
            "keyword_name"=> "updateProfile",
            "keyword_value"=> "Update Profile"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 376,
            "keyword_name"=> "add",
            "keyword_value"=> "Add"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 397,
            "keyword_name"=> "yourWeeklyReminderIsSetOn",
            "keyword_value"=> "your weekly reminder is set on"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 398,
            "keyword_name"=> "yourDailyReminderIsSetOn",
            "keyword_value"=> "your daily reminder is set on"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 399,
            "keyword_name"=> "enterYourMessageForReminder",
            "keyword_value"=> "Enter your message for reminder"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 400,
            "keyword_name"=> "weightReminders",
            "keyword_value"=> "Weight Reminders"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 401,
            "keyword_name"=> "trackYourWeight",
            "keyword_value"=> "Track your weight"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 402,
            "keyword_name"=> "drinkWaterReminders",
            "keyword_value"=> "Drink water Reminders"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 403,
            "keyword_name"=> "trackYourWaterIntake",
            "keyword_value"=> "Track your water intake"
          ],
          [
            "screenId"=> "51",
            "keyword_id"=> 404,
            "keyword_name"=> "trackYourSleepReminders",
            "keyword_value"=> "Track your sleep reminders"
          ]
        ]
      ],
      [
        "screenID"=> "52",
        "ScreenName"=> "EmailNewPinRequestDialog",
        "keyword_data"=> [
          [
            "screenId"=> "52",
            "keyword_id"=> 377,
            "keyword_name"=> "didYouForgetYourPin",
            "keyword_value"=> "Did you forget your pin?"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 378,
            "keyword_name"=> "enterEmailAddressToReceiveNewPin",
            "keyword_value"=> "Enter email address to receive new pin"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 379,
            "keyword_name"=> "invalidEmailAddress",
            "keyword_value"=> "Invalid email address"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 380,
            "keyword_name"=> "recover",
            "keyword_value"=> "Recover"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 381,
            "keyword_name"=> "emailValid",
            "keyword_value"=> "email valid"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 382,
            "keyword_name"=> "enterYourPin",
            "keyword_value"=> "Enter your pin"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 383,
            "keyword_name"=> "receivedAt",
            "keyword_value"=> "received at"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 384,
            "keyword_name"=> "forgotPin",
            "keyword_value"=> "Forgot Pin?"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 385,
            "keyword_name"=> "invalidPin",
            "keyword_value"=> "invalid pin"
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 386,
            "keyword_name"=> "enterYourEmailAddressAndCreateAPasswordTheBackupWillBeConfiguredForTheAccountAssociatedWithThisEmail",
            "keyword_value"=> "Enter your email address and create a password. The backup will be configured for the account associated with this email."
          ],
          [
            "screenId"=> "52",
            "keyword_id"=> 387,
            "keyword_name"=> "configureBackup",
            "keyword_value"=> "Configure backup"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 388,
            "keyword_name"=> "allTheSavedDataWillBeDeleted",
            "keyword_value"=> "All the saved data will be deleted"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 389,
            "keyword_name"=> "whenYouRestoreTheAppDataOnYourDeviceIsMergedWithTheLastBackedUpData",
            "keyword_value"=> "When you restore, the app data on your device is merged with the last backed up data"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 390,
            "keyword_name"=> "swipeToReadMore",
            "keyword_value"=> "Swipe to read more"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 391,
            "keyword_name"=> "clickToReadMore",
            "keyword_value"=> "Click to Read More"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 392,
            "keyword_name"=> "unlockPremiumFeatures",
            "keyword_value"=> "Unlock Premium Features"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 393,
            "keyword_name"=> "subscribeToAccessExclusiveContentAndFeatures",
            "keyword_value"=> "Subscribe to access exclusive content and features."
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 394,
            "keyword_name"=> "confirm",
            "keyword_value"=> "Confirm"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 394,
            "keyword_name"=> "confirm",
            "keyword_value"=> "Confirm"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 395,
            "keyword_name"=> "restartRequired",
            "keyword_value"=> "Restart Required"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 396,
            "keyword_name"=> "toApplyTheLanguageChanges",
            "keyword_value"=> "To apply the language changes, the app needs to restart. Do you want to restart now?"
          ]
        ]
      ],
      [
        "screenID"=> "53",
        "ScreenName"=> "DoctorDashboardScreen",
        "keyword_data"=> [
          [
            "screenId"=> "53",
            "keyword_id"=> 405,
            "keyword_name"=> "confirmExit",
            "keyword_value"=> "Confirm Exit"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 406,
            "keyword_name"=> "AreYouSureYouWantToExit",
            "keyword_value"=> "Are you sure you want to exit?"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 407,
            "keyword_name"=> "exit",
            "keyword_value"=> "exit"
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 408,
            "keyword_name"=> "noInternetConnectionCannotAccessThisPage",
            "keyword_value"=> "No internet connection. Cannot access this page."
          ],
          [
            "screenId"=> "53",
            "keyword_id"=> 409,
            "keyword_name"=> "dashboard",
            "keyword_value"=> "Dashboard"
          ]
        ]
      ],
      [
        "screenID"=> "54",
        "ScreenName"=> "BackupScreen",
        "keyword_data"=> [
          [
            "screenId"=> "54",
            "keyword_id"=> 426,
            "keyword_name"=> "backupSuccessText",
            "keyword_value"=> "Backup successful!"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 427,
            "keyword_name"=> "poweredByAI",
            "keyword_value"=> "Powered by AI"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 428,
            "keyword_name"=> "dailyTipsForYou",
            "keyword_value"=> "Daily tips for you"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 429,
            "keyword_name"=> "babyCountdown",
            "keyword_value"=> "Baby Countdown"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 430,
            "keyword_name"=> "SubscribeForAIInsights",
            "keyword_value"=> "Subscribe for AI insights, chats, and personalized info."
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 431,
            "keyword_name"=> "backupNotFound",
            "keyword_value"=> "Backup not found"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 432,
            "keyword_name"=> "backupPerFormedEvery",
            "keyword_value"=> "Backup performed every 12 hours"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 433,
            "keyword_name"=> "manualBackup",
            "keyword_value"=> "ManualBackup"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 434,
            "keyword_name"=> "createASecureBackupOfYourCurrentData",
            "keyword_value"=> "Create a secure backup of your current data"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 435,
            "keyword_name"=> "backingUp",
            "keyword_value"=> "Backing Up..."
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 436,
            "keyword_name"=> "backupNow",
            "keyword_value"=> "Backup Now"
          ],
          [
            "screenId"=> "54",
            "keyword_id"=> 437,
            "keyword_name"=> "lastBackup",
            "keyword_value"=> "Last Backup"
          ]
        ]
      ],
      [
        "screenID"=> "55",
        "ScreenName"=> "UserSignInScreen",
        "keyword_data"=> [
          [
            "screenId"=> "55",
            "keyword_id"=> 471,
            "keyword_name"=> "welcomeBack",
            "keyword_value"=> "Welcome Back"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 472,
            "keyword_name"=> "helloThereLoginInToContinue",
            "keyword_value"=> "Hello there, login in to continue!"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 473,
            "keyword_name"=> "signInWithGoogle",
            "keyword_value"=> "Sign in with Google"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 474,
            "keyword_name"=> "passwordIsRequired",
            "keyword_value"=> "Password is required"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 475,
            "keyword_name"=> "PasswordMustBeAtLeast",
            "keyword_value"=> "Password must be at least 6 characters"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 476,
            "keyword_name"=> "emailIsRequired",
            "keyword_value"=> "Email is required"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 477,
            "keyword_name"=> "pleaseEnterAValidEmail",
            "keyword_value"=> "Please enter a valid email"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 489,
            "keyword_name"=> "Account",
            "keyword_value"=> "Account"
          ],
          [
            "screenId"=> "55",
            "keyword_id"=> 490,
            "keyword_name"=> "subscribeToAskPersonalizedQuestionsToExperts",
            "keyword_value"=> "Subscribe to ask personalized questions to experts."
          ]
        ]
      ]
    ];
     
    foreach ($screen_data as $screen) {
      $screen_record = Screen::where('screenID', $screen['screenID'])->first();

      if ($screen_record == null) {
        $screen_record = Screen::create([
          'screenId'   => $screen['screenID'],
          'screenName' => $screen['ScreenName']
        ]);
      }

      if (isset($screen['keyword_data']) && count($screen['keyword_data']) > 0) {
        foreach ($screen['keyword_data'] as $keyword_data) {
          $keyword_record = DefaultKeyword::where('keyword_id', $keyword_data['keyword_id'])->first();

          if ($keyword_record == null) {
            $keyword_record = DefaultKeyword::create([
              'screen_id' => $screen_record['screenId'],
              'keyword_id' => $keyword_data['keyword_id'],
              'keyword_name' => $keyword_data['keyword_name'],
              'keyword_value' => $keyword_data['keyword_value']
            ]);
          }
        }
      }
    }
    $unmatchedKeywords = [];
    foreach ($screen_data as $screen) {
      $screen_record = Screen::where('screenID', $screen['screenID'])->first();
      if ($screen_record == null) {
        $screen_record = Screen::create([
          'screenId'   => $screen['screenID'],
          'screenName' => $screen['ScreenName']
        ]);
      }

      if (isset($screen['keyword_data']) && count($screen['keyword_data']) > 0) {
        foreach ($screen['keyword_data'] as $keyword_data) {
          $keyword_record = DefaultKeyword::where('keyword_id', $keyword_data['keyword_id'])->first();

          if ($keyword_record == null) {
            $keyword_record = DefaultKeyword::create([
              'screen_id' => $screen_record['screenId'],
              'keyword_id' => $keyword_data['keyword_id'],
              'keyword_name' => $keyword_data['keyword_name'],
              'keyword_value' => $keyword_data['keyword_value']
            ]);
          }
          if (!in_array($keyword_data['keyword_id'], $fetchedKeywords)) {
            $unmatchedKeywords[] = $keyword_data;

            foreach ($languageListIds as $languageId) {
                LanguageWithKeyword::create([
                    'screen_id'      => $keyword_data['screenId'],
                    'keyword_id'     => $keyword_data['keyword_id'],
                    'keyword_value'  => $keyword_data['keyword_value'],
                    'language_id'    => $languageId,
                ]);
            }
         }
        }
      }
    }
  }
}

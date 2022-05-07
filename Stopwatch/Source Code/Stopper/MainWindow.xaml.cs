using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Windows.Threading;
using System.Timers;
using System.Reflection;
using System.IO;
using System.Speech.Synthesis;


namespace Stopper
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {

        private TimeSpan S_interval = new TimeSpan();
        private TimeSpan S_total;

        private TimeSpan T_interval = new TimeSpan();
        private TimeSpan T_total;

        private DispatcherTimer stoper;
        private DispatcherTimer timer;

        bool StoperActivated = true;
        bool TimerActivated = true;


        public MainWindow()
        {
            InitializeComponent();

            stoper = new DispatcherTimer();
            stoper.Interval = TimeSpan.FromSeconds(1);
            stoper.Tick += new EventHandler(StoperTick);

            timer = new DispatcherTimer();
            timer.Interval = TimeSpan.FromSeconds(1);
            timer.Tick += new EventHandler(CounterTick);
        }

        

        #region In Menu functions

        private void Default_Click(object sender, RoutedEventArgs e)
        {
            mWindow.Height = 350;
            mWindow.Width = 250;
        }

        private void AProgram_Click(object sender, RoutedEventArgs e)
        {
            MessageBox.Show("  Timer/Stoper  |  Wersja 1.0  |  Sebastian Gondek 2018  ");
        }

        private void GreyOn_Click(object sender, RoutedEventArgs e)
        {
            if (menuWhite.IsChecked == true)
            {
                menuWhite.IsChecked = false;
            }
            menu.Background = Brushes.LightGray;
            mWindow.Background = Brushes.LightGray;
        }

        private void GreyOff_Click(object sender, RoutedEventArgs e)
        {
            menu.Background = Brushes.WhiteSmoke;
            mWindow.Background = Brushes.WhiteSmoke;
        }

        private void WhiteOn_Click(object sender, RoutedEventArgs e)
        {
            if(menuGrey.IsChecked == true)
            {
                menuGrey.IsChecked = false;
            }
            menu.Background = Brushes.White;
            mWindow.Background = Brushes.White;
        }

        private void WhiteOff_Click(object sender, RoutedEventArgs e)
        {
            menu.Background = Brushes.WhiteSmoke;
            mWindow.Background = Brushes.WhiteSmoke;
        }

        #endregion


        #region In TabControl Function

                    //Counter//

        private void BtnCountStart_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                T_total = TimeSpan.Parse(txtCountTime.Text);
            }
            catch (Exception ex)
            {
                MessageBox.Show("Podałeś nieprawidłowy format, zamień na hh:mm:ss");
            }
            

            if (T_total == TimeSpan.Parse("00:00:00"))
            {
                txtCountTime.Foreground = Brushes.DarkRed;
            }
            else
            {
                txtCountTime.IsReadOnly = true;
                txtCountTime.Foreground = Brushes.Black;

                if (TimerActivated == true)
                {
                    btnCountStart.Background = Brushes.OrangeRed;
                    btnCountStart.Content = "Stop";
                    TimerActivated = false;
                    timer.Start();
                }
                else
                {
                    btnCountStart.Background = Brushes.LimeGreen;
                    btnCountStart.Content = "Continue";
                    TimerActivated = true;
                    timer.Stop();
                }
            }
        }

        private void BtnCountReset_Click(object sender, RoutedEventArgs e)
        {
            CounterReset();
        }

        private void CounterTick(object sender, EventArgs e)
        {
            T_total = T_total - T_interval.Add(TimeSpan.FromMilliseconds(1000));

            txtCountTime.Text = T_total.ToString("hh\\:mm\\:ss");

            if (T_total == TimeSpan.Parse("00:00:10"))
            {
                SpeechSynthesizer synt = new SpeechSynthesizer();
                synt.Speak("ten seconds left");
            }

            if (T_total == TimeSpan.Parse("00:00:00"))
            {

                timer.Stop();

                SpeechSynthesizer synt = new SpeechSynthesizer();
                synt.Speak("Time is over");
                MessageBox.Show("Time is over");
                CounterReset();

            }

        }

        private void CounterReset()
        {
            txtCountTime.IsReadOnly = false;

            if (TimerActivated == true || TimerActivated == false)
            {
                btnCountStart.Background = Brushes.LimeGreen;
                btnCountStart.Content = "Count";
                TimerActivated = true;
                timer.Stop();
                txtCountTime.Text = String.Format("00:00:00");
                T_total = new TimeSpan();
            }
            else
            { }
        }


        //Stoper//

        private void BtnStart_Click(object sender, RoutedEventArgs e)
        {

            if (StoperActivated == true)
            {

                btnStart.Background = Brushes.OrangeRed;
                btnStart.Content = "Stop";
                StoperActivated = false;
                stoper.Start();
            }
            else
            {

                btnStart.Background = Brushes.LimeGreen;
                btnStart.Content = "Continue";
                StoperActivated = true;
                stoper.Stop();
            }
        }

        private void BtnReset_Click(object sender, RoutedEventArgs e)
        {
            if (StoperActivated == true || StoperActivated == false)
            {
                btnStart.Background = Brushes.LimeGreen;
                btnStart.Content = "Start";
                StoperActivated = true;
                stoper.Stop();
                txtTime.Text = String.Format("00:00:00");
                S_total = new TimeSpan();
            }
            else { }    
        }

        private void StoperTick(object sender, EventArgs e)
        {

            S_total = S_total + S_interval.Add(TimeSpan.FromMilliseconds(1000));

            txtTime.Text = S_total.ToString("hh\\:mm\\:ss");

        }


        #endregion

        
    }
}
import tkinter as tk
from tkinter import ttk, messagebox, scrolledtext
import sys
import os
from datetime import datetime
import pandas as pd
import webbrowser
import json

# Import the predictor class
from ga_fantasy5_predictor import GeorgiaFantasy5Predictor

class Fantasy5PredictionApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Georgia Fantasy 5 Prediction System")
        self.root.geometry("900x700")
        self.root.resizable(True, True)
        
        # Set app icon if available
        try:
            self.root.iconbitmap("lottery_icon.ico")
        except:
            pass
            
        # Create main frame
        self.main_frame = ttk.Frame(self.root, padding="10")
        self.main_frame.pack(fill=tk.BOTH, expand=True)
        
        # Create title label
        title_label = ttk.Label(
            self.main_frame, 
            text="Georgia Fantasy 5 Prediction System", 
            font=("Arial", 16, "bold")
        )
        title_label.pack(pady=10)
        
        # Create date label
        self.date_label = ttk.Label(
            self.main_frame,
            text=f"Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}",
            font=("Arial", 10)
        )
        self.date_label.pack(pady=5)
        
        # Create settings frame
        settings_frame = ttk.LabelFrame(self.main_frame, text="Settings", padding="10")
        settings_frame.pack(fill=tk.X, pady=10)
        
        # Database settings
        db_frame = ttk.Frame(settings_frame)
        db_frame.pack(fill=tk.X, pady=5)
        
        ttk.Label(db_frame, text="Database Host:").grid(row=0, column=0, padx=5, pady=5, sticky=tk.W)
        self.db_host = ttk.Entry(db_frame, width=20)
        self.db_host.insert(0, "localhost")
        self.db_host.grid(row=0, column=1, padx=5, pady=5, sticky=tk.W)
        
        ttk.Label(db_frame, text="Database Name:").grid(row=0, column=2, padx=5, pady=5, sticky=tk.W)
        self.db_name = ttk.Entry(db_frame, width=20)
        self.db_name.insert(0, "ga_f5_lotto")
        self.db_name.grid(row=0, column=3, padx=5, pady=5, sticky=tk.W)
        
        ttk.Label(db_frame, text="User:").grid(row=1, column=0, padx=5, pady=5, sticky=tk.W)
        self.db_user = ttk.Entry(db_frame, width=20)
        self.db_user.insert(0, "root")
        self.db_user.grid(row=1, column=1, padx=5, pady=5, sticky=tk.W)
        
        ttk.Label(db_frame, text="Password:").grid(row=1, column=2, padx=5, pady=5, sticky=tk.W)
        self.db_pass = ttk.Entry(db_frame, width=20, show="*")
        self.db_pass.grid(row=1, column=3, padx=5, pady=5, sticky=tk.W)
        
        # Filter settings
        filter_frame = ttk.LabelFrame(self.main_frame, text="Filter Settings", padding="10")
        filter_frame.pack(fill=tk.X, pady=10)
        
        ttk.Label(filter_frame, text="Max Sequential Pairs:").grid(row=0, column=0, padx=5, pady=5, sticky=tk.W)
        self.max_seq2 = ttk.Spinbox(filter_frame, from_=0, to=5, width=5)
        self.max_seq2.insert(0, "1")
        self.max_seq2.grid(row=0, column=1, padx=5, pady=5, sticky=tk.W)
        
        ttk.Label(filter_frame, text="Max Sequential Triplets:").grid(row=0, column=2, padx=5, pady=5, sticky=tk.W)
        self.max_seq3 = ttk.Spinbox(filter_frame, from_=0, to=5, width=5)
        self.max_seq3.insert(0, "0")
        self.max_seq3.grid(row=0, column=3, padx=5, pady=5, sticky=tk.W)
        
        ttk.Label(filter_frame, text="Max Mod Total:").grid(row=1, column=0, padx=5, pady=5, sticky=tk.W)
        self.max_mod_tot = ttk.Spinbox(filter_frame, from_=0, to=5, width=5)
        self.max_mod_tot.insert(0, "2")
        self.max_mod_tot.grid(row=1, column=1, padx=5, pady=5, sticky=tk.W)
        
        ttk.Label(filter_frame, text="Number of Predictions:").grid(row=1, column=2, padx=5, pady=5, sticky=tk.W)
        self.num_predictions = ttk.Spinbox(filter_frame, from_=1, to=100, width=5)
        self.num_predictions.insert(0, "10")
        self.num_predictions.grid(row=1, column=3, padx=5, pady=5, sticky=tk.W)
        
        # Sum range filter
        ttk.Label(filter_frame, text="Sum Range:").grid(row=2, column=0, padx=5, pady=5, sticky=tk.W)
        sum_frame = ttk.Frame(filter_frame)
        sum_frame.grid(row=2, column=1, columnspan=3, padx=5, pady=5, sticky=tk.W)
        
        self.min_sum = ttk.Spinbox(sum_frame, from_=5, to=195, width=5)
        self.min_sum.insert(0, "80")
        self.min_sum.pack(side=tk.LEFT, padx=5)
        
        ttk.Label(sum_frame, text="to").pack(side=tk.LEFT, padx=5)
        
        self.max_sum = ttk.Spinbox(sum_frame, from_=5, to=195, width=5)
        self.max_sum.insert(0, "120")
        self.max_sum.pack(side=tk.LEFT, padx=5)
        
        # Action buttons
        button_frame = ttk.Frame(self.main_frame)
        button_frame.pack(fill=tk.X, pady=10)
        
        self.generate_btn = ttk.Button(
            button_frame, 
            text="Generate Predictions", 
            command=self.generate_predictions
        )
        self.generate_btn.pack(side=tk.LEFT, padx=5)
        
        self.save_btn = ttk.Button(
            button_frame, 
            text="Save Predictions", 
            command=self.save_predictions,
            state=tk.DISABLED
        )
        self.save_btn.pack(side=tk.LEFT, padx=5)
        
        self.load_settings_btn = ttk.Button(
            button_frame, 
            text="Load Settings", 
            command=self.load_settings
        )
        self.load_settings_btn.pack(side=tk.LEFT, padx=5)
        
        self.save_settings_btn = ttk.Button(
            button_frame, 
            text="Save Settings", 
            command=self.save_settings
        )
        self.save_settings_btn.pack(side=tk.LEFT, padx=5)
        
        # Results area
        results_frame = ttk.LabelFrame(self.main_frame, text="Prediction Results", padding="10")
        results_frame.pack(fill=tk.BOTH, expand=True, pady=10)
        
        self.results_text = scrolledtext.ScrolledText(results_frame, wrap=tk.WORD, height=15)
        self.results_text.pack(fill=tk.BOTH, expand=True)
        
        # Status bar
        self.status_var = tk.StringVar()
        self.status_var.set("Ready")
        status_bar = ttk.Label(
            self.main_frame, 
            textvariable=self.status_var, 
            relief=tk.SUNKEN, 
            anchor=tk.W
        )
        status_bar.pack(fill=tk.X, side=tk.BOTTOM, pady=5)
        
        # Store predictions
        self.predictions = None
        
        # Load settings if available
        self.try_load_settings()
    
    def generate_predictions(self):
        """Generate predictions based on current settings"""
        try:
            # Get database settings
            db_config = {
                'host': self.db_host.get(),
                'user': self.db_user.get(),
                'password': self.db_pass.get(),
                'database': self.db_name.get()
            }
            
            # Update status
            self.status_var.set("Connecting to database...")
            self.root.update_idletasks()
            
            # Initialize predictor
            predictor = GeorgiaFantasy5Predictor(db_config)
            
            # Update status
            self.status_var.set("Generating predictions...")
            self.root.update_idletasks()
            
            # Override filter settings
            predictor.max_seq2 = int(self.max_seq2.get())
            predictor.max_seq3 = int(self.max_seq3.get())
            predictor.max_mod_tot = int(self.max_mod_tot.get())
            predictor.sum_range = (int(self.min_sum.get()), int(self.max_sum.get()))
            
            # Generate predictions
            count = int(self.num_predictions.get())
            self.predictions = predictor.generate_predictions(count=count)
            
            # Display predictions
            self.display_predictions()
            
            # Enable save button
            self.save_btn.config(state=tk.NORMAL)
            
            # Update status
            self.status_var.set(f"Generated {len(self.predictions)} predictions")
            
        except Exception as e:
            messagebox.showerror("Error", f"An error occurred: {str(e)}")
            self.status_var.set("Error generating predictions")
    
    def display_predictions(self):
        """Display predictions in the results text area"""
        if not self.predictions:
            return
            
        # Clear results area
        self.results_text.delete(1.0, tk.END)
        
        # Add header
        self.results_text.insert(tk.END, "============== GEORGIA FANTASY 5 PREDICTIONS ==============\n")
        self.results_text.insert(tk.END, f"Generated on: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        self.results_text.insert(tk.END, "==========================================================\n\n")
        
        # Add predictions
        for i, pred in enumerate(self.predictions, 1):
            combo = pred['combination']
            score = pred['score']
            total = pred['sum']
            
            line = f"Prediction #{i}: {combo[0]}-{combo[1]}-{combo[2]}-{combo[3]}-{combo[4]}\n"
            self.results_text.insert(tk.END, line)
            
            line = f"Sum: {total} | Score: {score:.2f}%\n"
            self.results_text.insert(tk.END, line)
            
            self.results_text.insert(tk.END, "----------------------------------------------------------\n")
    
    def save_predictions(self):
        """Save predictions to a CSV file"""
        if not self.predictions:
            messagebox.showinfo("Info", "No predictions to save")
            return
            
        try:
            from tkinter import filedialog
            
            # Get save path
            filename = filedialog.asksaveasfilename(
                initialdir="./",
                title="Save Predictions",
                filetypes=(("CSV files", "*.csv"), ("All files", "*.*")),
                defaultextension=".csv"
            )
            
            if not filename:
                return
                
            # Create DataFrame
            rows = []
            for i, pred in enumerate(self.predictions, 1):
                combo = pred['combination']
                row = {
                    'Prediction': i,
                    'Ball1': combo[0],
                    'Ball2': combo[1],
                    'Ball3': combo[2],
                    'Ball4': combo[3],
                    'Ball5': combo[4],
                    'Sum': pred['sum'],
                    'Score': pred['score'],
                    'Generated': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                }
                rows.append(row)
                
            df = pd.DataFrame(rows)
            df.to_csv(filename, index=False)
            
            self.status_var.set(f"Predictions saved to {filename}")
            messagebox.showinfo("Success", f"Predictions saved to {filename}")
            
        except Exception as e:
            messagebox.showerror("Error", f"An error occurred: {str(e)}")
            self.status_var.set("Error saving predictions")
    
    def save_settings(self):
        """Save current settings to a file"""
        try:
            settings = {
                'database': {
                    'host': self.db_host.get(),
                    'name': self.db_name.get(),
                    'user': self.db_user.get(),
                    'password': self.db_pass.get()
                },
                'filters': {
                    'max_seq2': self.max_seq2.get(),
                    'max_seq3': self.max_seq3.get(),
                    'max_mod_tot': self.max_mod_tot.get(),
                    'min_sum': self.min_sum.get(),
                    'max_sum': self.max_sum.get(),
                    'num_predictions': self.num_predictions.get()
                }
            }
            
            with open('ga_f5_settings.json', 'w') as f:
                json.dump(settings, f, indent=4)
                
            self.status_var.set("Settings saved")
            messagebox.showinfo("Success", "Settings saved successfully")
            
        except Exception as e:
            messagebox.showerror("Error", f"An error saving settings: {str(e)}")
    
    def load_settings(self):
        """Load settings from a file"""
        try:
            from tkinter import filedialog
            
            # Get file path
            filename = filedialog.askopenfilename(
                initialdir="./",
                title="Load Settings",
                filetypes=(("JSON files", "*.json"), ("All files", "*.*"))
            )
            
            if not filename:
                return
                
            with open(filename, 'r') as f:
                settings = json.load(f)
                
            # Apply settings
            self.apply_settings(settings)
            
            self.status_var.set("Settings loaded")
            
        except Exception as e:
            messagebox.showerror("Error", f"An error loading settings: {str(e)}")
    
    def try_load_settings(self):
        """Try to load settings from default file"""
        try:
            if os.path.exists('ga_f5_settings.json'):
                with open('ga_f5_settings.json', 'r') as f:
                    settings = json.load(f)
                    
                # Apply settings
                self.apply_settings(settings)
                
                self.status_var.set("Default settings loaded")
        except:
            # Silently fail - use defaults
            pass
    
    def apply_settings(self, settings):
        """Apply loaded settings to UI elements"""
        # Database settings
        if 'database' in settings:
            db = settings['database']
            if 'host' in db:
                self.db_host.delete(0, tk.END)
                self.db_host.insert(0, db['host'])
            if 'name' in db:
                self.db_name.delete(0, tk.END)
                self.db_name.insert(0, db['name'])
            if 'user' in db:
                self.db_user.delete(0, tk.END)
                self.db_user.insert(0, db['user'])
            if 'password' in db:
                self.db_pass.delete(0, tk.END)
                self.db_pass.insert(0, db['password'])
                
        # Filter settings
        if 'filters' in settings:
            filters = settings['filters']
            if 'max_seq2' in filters:
                self.max_seq2.delete(0, tk.END)
                self.max_seq2.insert(0, filters['max_seq2'])
            if 'max_seq3' in filters:
                self.max_seq3.delete(0, tk.END)
                self.max_seq3.insert(0, filters['max_seq3'])
            if 'max_mod_tot' in filters:
                self.max_mod_tot.delete(0, tk.END)
                self.max_mod_tot.insert(0, filters['max_mod_tot'])
            if 'min_sum' in filters:
                self.min_sum.delete(0, tk.END)
                self.min_sum.insert(0, filters['min_sum'])
            if 'max_sum' in filters:
                self.max_sum.delete(0, tk.END)
                self.max_sum.insert(0, filters['max_sum'])
            if 'num_predictions' in filters:
                self.num_predictions.delete(0, tk.END)
                self.num_predictions.insert(0, filters['num_predictions'])

# Main application entry point
if __name__ == "__main__":
    root = tk.Tk()
    app = Fantasy5PredictionApp(root)
    root.mainloop()
